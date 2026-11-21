<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderWriter
{
    public static function paise(float $rupees): int
    {
        return (int) round($rupees * 100);
    }

    public static function place(array $data, array $quote, int $bookingType, bool $deductNow): array
    {
        $orderNumber = now()->format('YmdHis').random_int(10, 99);

        return DB::transaction(function () use ($data, $quote, $bookingType, $deductNow, $orderNumber) {
            $items = $quote['items'];
            $addressId = ((int) DB::table('address')->lockForUpdate()->max('id')) + 1;
            DB::table('address')->insert([
                'id' => $addressId,
                'user_id' => auth()->id() ?: 0,
                'name' => $data['name'],
                'mobile_number' => $data['mobile_number'],
                'address' => $data['address'],
                'pincode' => (int) $data['pincode'],
                'locality' => $data['locality'],
                'city' => $data['city'],
                'state_id' => (string) $data['state_id'],
                'address_type' => 1,
                'default_type' => 2,
                'created_date' => now(),
            ]);

            $orderId = DB::table('orders')->insertGetId([
                'session_id' => session()->getId(),
                'order_number' => $orderNumber,
                'user_id' => auth()->id() ?: 0,
                'address_id' => $addressId,
                'order_status' => 1,
                'payment_status' => 1,
                'delivery_status' => 1,
                'booking_date' => now(),
                'booking_type' => $bookingType,
                'delivery_date' => now()->addDays(5),
            ]);

            $detailId = (int) DB::table('order_details')->max('id');
            $lines = [];
            foreach ($items as $item) {
                $detailId++;
                DB::table('order_details')->insert([
                    'id' => $detailId,
                    'order_id' => $orderId,
                    'user_id' => auth()->id() ?: 0,
                    'product_id' => $item->id,
                    'product_title' => $item->product_title,
                    'image' => $item->image_name ?: 'no-image.jpg',
                    'order_quantity' => $item->qty,
                    'amount_per_item' => (string) (int) $item->price,
                ]);
                $lines[] = [
                    'product_id' => (int) $item->id,
                    'color_id' => $item->color_id ? (int) $item->color_id : null,
                    'qty' => (int) $item->qty,
                ];
                if ($deductNow) {
                    Inventory::deduct((int) $item->id, $item->color_id ? (int) $item->color_id : null, (int) $item->qty, (int) $orderId);
                }
            }

            $paymentId = ((int) DB::table('payments')->max('payment_id')) + 1;
            $payment = [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'total_amount' => (string) (int) round($quote['total']),
                'amount_recieved' => '0',
                'discount_percent' => null,
                'payment_status' => 1,
                'created_date' => now(),
            ];
            if (Schema::hasColumn('payments', 'amount')) {
                $payment['amount'] = round((float) $quote['total'], 2);
                $payment['currency'] = 'INR';
                $payment['payment_gateway'] = $bookingType === 2 ? 'cod' : null;
                $payment['payment_state'] = 'pending';
                $payment['idempotency_key'] = 'order:'.$orderNumber;
                $payment['ip_address'] = request()?->ip();
                $payment['user_agent'] = substr((string) request()?->userAgent(), 0, 2000);
            }
            DB::table('payments')->insert($payment);
            if (Schema::hasTable('order_adjustments')) {
                DB::table('order_adjustments')->insert([
                    'order_id' => $orderId,
                    'subtotal' => $quote['subtotal'],
                    'discount' => $quote['discount'],
                    'tax' => $quote['tax'],
                    'total' => $quote['total'],
                    'coupon_code' => $quote['coupon'],
                    'tax_label' => $quote['tax_label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($deductNow) {
                self::redeemCoupon($quote, $orderId);
                AuditLog::write('order.cod_placed', 'order', $orderId, ['order_number' => $orderNumber]);
            }

            return [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'lines' => $lines,
                'amount_paise' => self::paise((float) $quote['total']),
            ];
        });
    }

    public static function redeemCoupon(array $quote, int $orderId): void
    {
        if (! $quote['coupon'] || ! Schema::hasTable('coupons') || ! Schema::hasTable('coupon_redemptions')) {
            return;
        }
        $coupon = DB::table('coupons')->where('code', $quote['coupon'])->first();
        if (! $coupon) {
            return;
        }
        $already = DB::table('coupon_redemptions')->where('order_id', $orderId)->exists();
        if ($already) {
            return;
        }
        DB::table('coupon_redemptions')->insert([
            'coupon_id' => $coupon->id,
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'amount' => $quote['discount'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
