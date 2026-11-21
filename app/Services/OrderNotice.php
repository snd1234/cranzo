<?php

namespace App\Services;

use App\Jobs\SendOrderMail;
use App\Mail\OrderNoticeMail;
use App\Support\OrderLabel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class OrderNotice
{
    public static function queue(int $orderId, string $kind): void
    {
        try {
            SendOrderMail::dispatch($orderId, $kind)->afterCommit();
        } catch (Throwable $exception) {
            Log::warning('order.email.failed', [
                'order_id' => $orderId,
                'kind' => $kind,
                'error' => $exception::class,
            ]);
        }
    }

    public function deliver(int $orderId, string $kind): void
    {
        $order = $this->payload($orderId, $kind);
        if (! $order) {
            return;
        }

        if ($order['customer_email']) {
            $this->once($orderId, $kind, 'customer', function () use ($order, $kind) {
                Mail::to($order['customer_email'])->send(new OrderNoticeMail(
                    $kind === 'failed' ? 'payment-failed' : ($kind === 'paid' ? 'payment-success' : 'order-confirmation'),
                    $this->subject($order, $kind, false),
                    $order
                ));
            });
        }

        if ($order['admin_email']) {
            $this->once($orderId, $kind, 'admin', function () use ($order, $kind) {
                Mail::to($order['admin_email'])->send(new OrderNoticeMail(
                    'admin-notification',
                    $this->subject($order, $kind, true),
                    $order
                ));
            });
        }
    }

    private function once(int $orderId, string $kind, string $audience, callable $send): void
    {
        if (Schema::hasTable('order_notifications')) {
            $sent = DB::table('order_notifications')
                ->where('order_id', $orderId)
                ->where('kind', $kind)
                ->where('audience', $audience)
                ->whereNotNull('sent_at')
                ->exists();
            if ($sent) {
                return;
            }
        }

        try {
            $send();
        } catch (Throwable $exception) {
            Log::warning('order.email.failed', [
                'order_id' => $orderId,
                'kind' => $kind,
                'audience' => $audience,
                'error' => $exception::class,
            ]);
            throw $exception;
        }

        if (Schema::hasTable('order_notifications')) {
            DB::table('order_notifications')->updateOrInsert(
                ['order_id' => $orderId, 'kind' => $kind, 'audience' => $audience],
                ['sent_at' => now(), 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    private function payload(int $orderId, string $kind): ?array
    {
        $order = DB::table('orders')->where('id', $orderId)->first();
        if (! $order) {
            return null;
        }

        $address = DB::table('address')->where('id', $order->address_id)->first();
        $state = $address ? DB::table('state')->where('id', $address->state_id)->value('state_name') : null;
        $adjust = Schema::hasTable('order_adjustments')
            ? DB::table('order_adjustments')->where('order_id', $order->id)->first()
            : null;
        $payment = DB::table('payments')->where('order_id', $order->id)->first();
        $transaction = Schema::hasTable('payment_transactions')
            ? DB::table('payment_transactions')->where('order_id', $order->id)->orderByDesc('id')->first()
            : null;
        $customerEmail = (int) $order->user_id > 0
            ? DB::table('users')->where('id', $order->user_id)->value('email')
            : null;

        $lines = DB::table('order_details')->where('order_id', $order->id)->get()->map(function ($line) {
            $sku = Schema::hasColumn('product', 'sku_id')
                ? DB::table('product')->where('id', $line->product_id)->value('sku_id')
                : null;
            $image = null;
            if (! empty($line->image) && $line->image !== 'no-image.jpg' && is_file(public_path('uploads/products/'.$line->image))) {
                $image = asset('uploads/products/'.$line->image);
            }
            $price = (float) $line->amount_per_item;

            return [
                'title' => (string) $line->product_title,
                'sku' => $sku ? (string) $sku : null,
                'qty' => (int) $line->order_quantity,
                'price' => $price,
                'total' => round($price * (int) $line->order_quantity, 2),
                'image' => $image,
            ];
        })->all();

        $paid = (int) $order->payment_status === 2;
        $cod = (int) $order->booking_type === 2;
        $reason = $transaction && $transaction->failure_reason
            ? $this->clean((string) $transaction->failure_reason)
            : null;
        if ($reason === 'Payment failed' || $reason === 'Payment was not captured.') {
            $reason = null;
        }

        $support = $this->publicAddress((string) config('mail.from.address'));

        return [
            'kind' => $kind,
            'logo' => asset('front/images/cranzo-logo.jpg'),
            'store' => 'Cranzo',
            'support_email' => $support,
            'order_number' => (string) $order->order_number,
            'order_id' => (int) $order->id,
            'placed_at' => (string) $order->booking_date,
            'customer_name' => $address->name ?? 'there',
            'customer_email' => $this->publicAddress((string) $customerEmail),
            'customer_phone' => $address->mobile_number ?? null,
            'address' => $address ? trim($address->address.', '.$address->locality.', '.$address->city.($state ? ', '.$state : '').' '.$address->pincode) : null,
            'lines' => $lines,
            'subtotal' => (float) ($adjust->subtotal ?? 0),
            'discount' => (float) ($adjust->discount ?? 0),
            'shipping' => 0.0,
            'tax' => (float) ($adjust->tax ?? 0),
            'tax_label' => $adjust->tax_label ?? 'GST',
            'coupon' => $adjust->coupon_code ?? null,
            'total' => (float) ($adjust->total ?? ($payment->total_amount ?? 0)),
            'payment_method' => $cod ? 'Cash on Delivery' : 'Razorpay',
            'payment_status' => $kind === 'failed' ? 'Unsuccessful' : ($paid ? 'Paid' : 'Pending'),
            'transaction_id' => ($payment && Schema::hasColumn('payments', 'gateway_transaction_id') && $payment->gateway_transaction_id)
                ? $payment->gateway_transaction_id
                : ($transaction->razorpay_payment_id ?? null),
            'order_status' => OrderLabel::summary($order),
            'delivery_date' => $order->delivery_date ?: null,
            'view_url' => (int) $order->user_id > 0 ? route('account.order', $order->id) : null,
            'retry_url' => $kind === 'failed' && (int) $order->user_id > 0 && ! $paid ? route('account.order.retry', $order->id) : null,
            'failure_reason' => $kind === 'failed' ? $reason : null,
            'admin_email' => $this->adminAddress(),
            'year' => now()->year,
        ];
    }

    private function subject(array $order, string $kind, bool $admin): string
    {
        $number = $order['order_number'];
        if ($admin) {
            $event = match ($kind) {
                'failed' => 'payment failed',
                'paid' => 'payment received',
                default => $order['payment_method'] === 'Cash on Delivery' ? 'new cash on delivery order' : 'new order',
            };

            return 'Cranzo order '.$number.': '.$event;
        }

        return match ($kind) {
            'failed' => 'Payment was not completed for order '.$number,
            'paid' => 'Payment received for order '.$number,
            default => 'Order '.$number.' placed',
        };
    }

    private function adminAddress(): ?string
    {
        $configured = config('mail.order_notify');
        $address = is_string($configured) && $configured !== '' ? $configured : (string) config('mail.from.address');

        return $this->publicAddress($address);
    }

    private function publicAddress(string $address): ?string
    {
        $address = trim($address);
        if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        $domain = strtolower((string) substr(strrchr($address, '@'), 1));
        if (in_array($domain, ['example.com', 'example.net', 'example.org'], true)) {
            return null;
        }

        return $address;
    }

    private function clean(string $value): ?string
    {
        $value = trim(preg_replace('/\s+/', ' ', strip_tags($value)) ?? '');
        if ($value === '') {
            return null;
        }

        return mb_substr($value, 0, 180);
    }
}
