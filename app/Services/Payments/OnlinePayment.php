<?php

namespace App\Services\Payments;

use App\Services\AuditLog;
use App\Services\CheckoutQuote;
use App\Services\Inventory;
use App\Services\OrderNotice;
use App\Services\OrderWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OnlinePayment
{
    public function __construct(private RazorpayClient $razorpay) {}

    public function configured(): bool
    {
        return filled(config('razorpay.key_id')) && filled(config('razorpay.key_secret'));
    }

    public function start(array $address): array
    {
        if (! $this->configured() || ! Schema::hasTable('payment_transactions')) {
            throw new PaymentException('Online payment is not configured.');
        }

        $quote = CheckoutQuote::summary((int) $address['state_id']);
        if ($quote['items']->isEmpty()) {
            throw new PaymentException('Your cart is empty.');
        }
        if ($quote['coupon_error']) {
            throw new PaymentException($quote['coupon_error']);
        }
        $paise = OrderWriter::paise((float) $quote['total']);
        if ($paise < 100) {
            throw new PaymentException('Online payment is available for orders of at least ₹1.');
        }

        $order = $this->reusableOrder($paise);
        if (! $order) {
            $this->closeStaleAttempt();
            try {
                $placed = OrderWriter::place($address, $quote, 1, false);
            } catch (\RuntimeException $exception) {
                throw new PaymentException('Not enough stock to place this order.');
            }
            $order = DB::table('orders')->where('id', $placed['order_id'])->first();
            $lines = $placed['lines'];
        } else {
            $lines = $this->linesFor($order->id);
        }

        $receipt = substr($order->order_number.'-'.now()->format('Hisv'), 0, 40);
        $remote = $this->razorpay->createOrder($paise, $receipt, [
            'order_number' => (string) $order->order_number,
        ]);
        if ((int) $remote['amount'] !== $paise || $remote['currency'] !== 'INR') {
            throw new PaymentException('Payment could not be started.');
        }

        $transaction = [
            'order_id' => $order->id,
            'provider' => 'razorpay',
            'razorpay_order_id' => $remote['id'],
            'amount_paise' => $paise,
            'currency' => 'INR',
            'status' => 'created',
            'meta' => json_encode([
                'lines' => $lines,
                'coupon' => $quote['coupon'],
                'discount' => $quote['discount'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('payment_transactions', 'idempotency_key')) {
            $transaction['idempotency_key'] = 'razorpay:'.$remote['id'];
            $transaction['amount'] = round($paise / 100, 2);
            $transaction['ip_address'] = request()?->ip();
            $transaction['user_agent'] = substr((string) request()?->userAgent(), 0, 2000);
            $transaction['gateway_response'] = json_encode([
                'id' => $remote['id'],
                'amount' => $remote['amount'],
                'currency' => $remote['currency'],
            ]);
        }
        DB::table('payment_transactions')->insert($transaction);
        session(['pending_online_order' => $order->order_number]);

        return [
            'key' => (string) config('razorpay.key_id'),
            'amount' => $paise,
            'currency' => 'INR',
            'razorpay_order_id' => $remote['id'],
            'order_number' => $order->order_number,
            'name' => 'Cranzo',
            'description' => 'Order '.$order->order_number,
            'prefill' => [
                'name' => $address['name'],
                'contact' => $address['mobile_number'],
                'email' => auth()->user()->email ?? '',
            ],
        ];
    }

    public function verify(string $orderNumber, string $razorpayOrderId, string $paymentId, string $signature): object
    {
        $order = $this->ownedOrder($orderNumber);
        $this->razorpay->verifyPaymentSignature($razorpayOrderId, $paymentId, $signature);
        $payment = $this->razorpay->fetchPayment($paymentId);
        if ($payment['id'] !== $paymentId || $payment['order_id'] !== $razorpayOrderId) {
            throw new PaymentException('Payment could not be confirmed.');
        }
        $this->confirm($order, $payment);
        $fresh = DB::table('orders')->where('id', $order->id)->first();
        if ((int) $fresh->payment_status !== 2) {
            throw new PaymentException('Payment could not be confirmed.');
        }

        return $fresh;
    }

    public function cancel(string $orderNumber): void
    {
        $order = $this->ownedOrder($orderNumber);
        if ((int) $order->payment_status === 2) {
            return;
        }
        DB::table('payment_transactions')
            ->where('order_id', $order->id)
            ->where('status', 'created')
            ->update(['status' => 'cancelled', 'updated_at' => now()]);
    }

    public function handleWebhook(string $body, string $signature, string $eventId): void
    {
        $this->razorpay->verifyWebhookSignature($body, $signature);
        $payload = json_decode($body, true);
        if (! is_array($payload) || empty($payload['event'])) {
            throw new PaymentException('Payment could not be confirmed.');
        }
        $eventId = $eventId !== '' ? substr($eventId, 0, 80) : hash('sha256', $body);

        DB::transaction(function () use ($payload, $eventId) {
            $seen = DB::table('payment_webhook_events')->where('event_id', $eventId)->lockForUpdate()->exists();
            if ($seen) {
                return;
            }
            $payment = $payload['payload']['payment']['entity'] ?? null;
            $event = (string) $payload['event'];
            if (in_array($event, ['payment.authorized', 'payment.captured', 'payment.failed', 'order.paid'], true) && is_array($payment)) {
                try {
                    $this->applyWebhookPayment($event, $payment);
                } catch (PaymentException $exception) {
                    Log::warning('payment.webhook.rejected', ['event' => $event]);
                }
            }
            DB::table('payment_webhook_events')->insert([
                'event_id' => $eventId,
                'event_type' => $event,
                'razorpay_order_id' => is_array($payment) ? ($payment['order_id'] ?? null) : null,
                'razorpay_payment_id' => is_array($payment) ? ($payment['id'] ?? null) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function paidOrderFromSession(): ?object
    {
        $number = session('pending_online_order');
        if (! $number) {
            return null;
        }
        $order = DB::table('orders')->where('order_number', $number)->first();
        if (! $order || ! $this->owns($order) || (int) $order->payment_status !== 2) {
            return null;
        }

        return $order;
    }

    private function applyWebhookPayment(string $event, array $payment): void
    {
        $normalized = [
            'id' => (string) ($payment['id'] ?? ''),
            'order_id' => (string) ($payment['order_id'] ?? ''),
            'amount' => (int) ($payment['amount'] ?? 0),
            'currency' => (string) ($payment['currency'] ?? ''),
            'status' => (string) ($payment['status'] ?? ''),
        ];
        if ($normalized['id'] === '' || $normalized['order_id'] === '') {
            return;
        }
        $tx = DB::table('payment_transactions')->where('razorpay_order_id', $normalized['order_id'])->first();
        if (! $tx) {
            Log::warning('payment.webhook.unknown', ['razorpay_order_id' => $normalized['order_id']]);

            return;
        }
        $order = DB::table('orders')->where('id', $tx->order_id)->first();
        if (! $order) {
            return;
        }
        if ($event === 'payment.failed' || $normalized['status'] === 'failed') {
            $reason = substr(trim(strip_tags((string) ($payment['error_description'] ?? $payment['error_reason'] ?? ''))), 0, 255);
            $updated = DB::table('payment_transactions')->where('id', $tx->id)->where('status', 'created')->update([
                'status' => 'failed',
                'razorpay_payment_id' => $normalized['id'],
                'failure_reason' => $reason !== '' ? $reason : 'Payment failed',
                'updated_at' => now(),
            ]);
            if ($updated) {
                $this->recordFailure($order, $reason);
            }

            return;
        }
        if (in_array($normalized['status'], ['authorized', 'captured'], true) || $event === 'order.paid') {
            $remote = $this->razorpay->fetchPayment($normalized['id']);
            $this->confirm($order, $remote);
        }
    }

    private function confirm(object $order, array $payment): void
    {
        $tx = DB::table('payment_transactions')->where('razorpay_order_id', $payment['order_id'])->first();
        if (! $tx || (int) $tx->order_id !== (int) $order->id) {
            throw new PaymentException('Payment could not be confirmed.');
        }
        if ((int) $payment['amount'] !== (int) $tx->amount_paise || $payment['currency'] !== 'INR') {
            Log::warning('payment.amount.mismatch', [
                'order_number' => $order->order_number,
                'expected' => (int) $tx->amount_paise,
                'received' => (int) $payment['amount'],
            ]);
            throw new PaymentException('Payment could not be confirmed.');
        }
        $adjust = Schema::hasTable('order_adjustments')
            ? DB::table('order_adjustments')->where('order_id', $order->id)->first()
            : null;
        if ($adjust && OrderWriter::paise((float) $adjust->total) !== (int) $tx->amount_paise) {
            throw new PaymentException('Payment could not be confirmed.');
        }

        if ($payment['status'] === 'authorized') {
            $payment = $this->razorpay->capture($payment['id'], (int) $tx->amount_paise);
        }
        if ($payment['status'] !== 'captured') {
            DB::table('payment_transactions')->where('id', $tx->id)->where('status', '!=', 'captured')->update([
                'status' => 'failed',
                'razorpay_payment_id' => $payment['id'],
                'failure_reason' => 'Payment was not captured.',
                'updated_at' => now(),
            ]);
            $this->recordFailure($order, '');
            throw new PaymentException('Payment was not completed.');
        }

        $becamePaid = false;
        try {
        DB::transaction(function () use ($order, $payment, $tx, &$becamePaid) {
            $locked = DB::table('orders')->where('id', $order->id)->lockForUpdate()->first();
            $captured = DB::table('payment_transactions')
                ->where('order_id', $locked->id)
                ->where('status', 'captured')
                ->first();
            if ($captured && $captured->razorpay_payment_id === $payment['id']) {
                return;
            }
            if ($captured || (int) $locked->payment_status === 2) {
                $this->refundExtra($tx->id, $payment);
                return;
            }

            $lines = $this->linesFor((int) $locked->id);
            if (! Inventory::alreadyDeducted((int) $locked->id)) {
                foreach ($lines as $line) {
                    Inventory::deduct((int) $line['product_id'], $line['color_id'], (int) $line['qty'], (int) $locked->id);
                }
            }
            DB::table('orders')->where('id', $locked->id)->update(['payment_status' => 2]);
            $paymentUpdate = [
                'amount_recieved' => (string) (int) round(((int) $tx->amount_paise) / 100),
                'payment_status' => 2,
            ];
            if (Schema::hasColumn('payments', 'payment_state')) {
                $paymentUpdate['payment_gateway'] = 'razorpay';
                $paymentUpdate['gateway_transaction_id'] = $payment['id'];
                $paymentUpdate['payment_state'] = 'succeeded';
                $paymentUpdate['amount'] = round(((int) $tx->amount_paise) / 100, 2);
            }
            DB::table('payments')->where('order_id', $locked->id)->update($paymentUpdate);
            DB::table('payment_transactions')->where('id', $tx->id)->update([
                'razorpay_payment_id' => $payment['id'],
                'status' => 'captured',
                'verified_at' => now(),
                'updated_at' => now(),
            ]);
            $meta = json_decode((string) $tx->meta, true) ?: [];
            OrderWriter::redeemCoupon([
                'coupon' => $meta['coupon'] ?? null,
                'discount' => $meta['discount'] ?? 0,
            ], (int) $locked->id);
            $becamePaid = true;
        });
        } catch (\RuntimeException $exception) {
            $this->refundExtra((int) $tx->id, $payment);
            throw new PaymentException('Not enough stock to place this order.');
        }

        if ($becamePaid) {
            OrderNotice::queue((int) $order->id, 'placed');
            OrderNotice::queue((int) $order->id, 'paid');
            Log::info('payment.captured', ['order_number' => $order->order_number, 'payment_id' => $payment['id']]);
            AuditLog::write('payment.captured', 'order', (int) $order->id, ['payment_id' => $payment['id']]);
        }
    }

    private function recordFailure(object $order, string $reason): void
    {
        if ((int) $order->payment_status === 2) {
            return;
        }
        if (Schema::hasColumn('payments', 'payment_state')) {
            $update = ['payment_state' => 'failed'];
            if ($reason !== '' && Schema::hasColumn('payments', 'failure_reason')) {
                $update['failure_reason'] = $reason;
            }
            DB::table('payments')->where('order_id', $order->id)->where('payment_status', 1)->update($update);
        }
        OrderNotice::queue((int) $order->id, 'failed');
    }

    private function refundExtra(int $transactionId, array $payment): void
    {
        $status = 'refunded';
        try {
            $this->razorpay->refund($payment['id'], (int) $payment['amount']);
        } catch (\Throwable $exception) {
            $status = 'refund_required';
            Log::error('payment.duplicate.refund_required', ['payment_id' => $payment['id']]);
        }
        $current = DB::table('payment_transactions')->where('id', $transactionId)->first();
        if ($current && $current->status === 'captured') {
            return;
        }
        DB::table('payment_transactions')->where('id', $transactionId)->update([
            'razorpay_payment_id' => $payment['id'],
            'status' => $status,
            'failure_reason' => 'Duplicate captured payment.',
            'updated_at' => now(),
        ]);
    }

    private function reusableOrder(int $paise): ?object
    {
        $number = session('pending_online_order');
        if (! $number) {
            return null;
        }
        $order = DB::table('orders')->where('order_number', $number)->whereNull('cancelled_at')->first();
        if (! $order || ! $this->owns($order) || (int) $order->booking_type !== 1 || (int) $order->payment_status !== 1) {
            return null;
        }
        $total = Schema::hasTable('order_adjustments')
            ? DB::table('order_adjustments')->where('order_id', $order->id)->value('total')
            : null;
        if ($total === null || OrderWriter::paise((float) $total) !== $paise) {
            return null;
        }

        return $order;
    }

    private function closeStaleAttempt(): void
    {
        $number = session('pending_online_order');
        if (! $number) {
            return;
        }
        $order = DB::table('orders')->where('order_number', $number)->first();
        if ($order && $this->owns($order) && (int) $order->payment_status === 1 && (int) $order->booking_type === 1 && ! $order->cancelled_at) {
            DB::table('orders')->where('id', $order->id)->update(['cancelled_at' => now()]);
        }
        session()->forget('pending_online_order');
    }

    private function linesFor(int $orderId): array
    {
        $tx = DB::table('payment_transactions')->where('order_id', $orderId)->orderByDesc('id')->first();
        $meta = $tx ? json_decode((string) $tx->meta, true) : null;
        if (is_array($meta) && ! empty($meta['lines'])) {
            return $meta['lines'];
        }

        return DB::table('order_details')->where('order_id', $orderId)->get()->map(fn ($line) => [
            'product_id' => (int) $line->product_id,
            'color_id' => null,
            'qty' => (int) $line->order_quantity,
        ])->all();
    }

    private function ownedOrder(string $orderNumber): object
    {
        $order = DB::table('orders')->where('order_number', $orderNumber)->first();
        if (! $order || ! $this->owns($order) || (int) $order->booking_type !== 1) {
            throw new PaymentException('Payment could not be confirmed.');
        }

        return $order;
    }

    private function owns(object $order): bool
    {
        if (auth()->check()) {
            return (int) $order->user_id === (int) auth()->id();
        }

        return (int) $order->user_id === 0
            && is_string($order->session_id)
            && $order->session_id !== ''
            && hash_equals($order->session_id, session()->getId());
    }
}
