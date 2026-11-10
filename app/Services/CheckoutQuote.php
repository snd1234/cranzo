<?php

namespace App\Services;

use App\Support\Money;
use Illuminate\Support\Facades\DB;

class CheckoutQuote
{
    public static function items()
    {
        $cart = session('shop_cart', []);
        if ($cart === []) {
            return collect();
        }

        $ids = collect(array_keys($cart))->map(fn ($id) => (int) $id)->all();
        $products = DB::table('product')->whereIn('id', $ids)->where('status', 1)->get()->keyBy('id');
        $images = DB::table('product_images')->whereIn('product_id', $ids)->get()->groupBy('product_id');

        return collect($cart)->map(function ($row, $id) use ($products, $images) {
            $product = $products->get((int) $id);
            if (! $product) {
                return null;
            }
            $colorId = isset($row['color_id']) ? (int) $row['color_id'] : null;
            $stockQuery = DB::table('product_details')->where('product_id', $product->id);
            if ($colorId) {
                $stockQuery->where('color_id', $colorId);
            }
            $stock = (int) $stockQuery->sum('product_quantity');
            $qty = min(10, max(1, (int) ($row['qty'] ?? 1)));
            if ($stock < 1) {
                return null;
            }
            $qty = min($qty, $stock);
            $product->qty = $qty;
            $product->color_id = $colorId;
            $product->color_name = $colorId ? DB::table('colors')->where('color_id', $colorId)->value('color_title') : null;
            $product->price = (float) $product->discounted_price;
            $product->line_total = round($product->price * $qty, 2);
            $image = $images->get($product->id, collect())->first();
            $product->image_name = $image->image ?? $image->thumbnail ?? null;
            $product->gst_rate = isset($product->gst_rate) ? $product->gst_rate : null;

            return $product;
        })->filter()->values();
    }

    public static function summary(?int $stateId = null): array
    {
        $items = self::items();
        $subtotal = round((float) $items->sum('line_total'), 2);
        $coupon = self::coupon($items, $subtotal);
        $discount = $coupon['discount'];
        $taxable = max(0, $subtotal - $discount);
        $tax = self::tax($items, $subtotal, $discount, $stateId);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax['amount'],
            'tax_label' => $tax['label'],
            'total' => round($taxable + $tax['amount'], 2),
            'coupon' => $coupon['code'],
            'coupon_error' => $coupon['error'],
        ];
    }

    public static function coupon($items, float $subtotal): array
    {
        $code = session('shop_coupon');
        if (! $code || ! \Illuminate\Support\Facades\Schema::hasTable('coupons')) {
            return ['code' => null, 'discount' => 0.0, 'error' => null, 'id' => null];
        }

        $coupon = DB::table('coupons')->where('code', $code)->where('status', 1)->first();
        if (! $coupon) {
            session()->forget('shop_coupon');

            return ['code' => null, 'discount' => 0.0, 'error' => 'That coupon is not active.', 'id' => null];
        }
        $now = now();
        if (($coupon->starts_at && $now->lt($coupon->starts_at)) || ($coupon->ends_at && $now->gt($coupon->ends_at))) {
            return ['code' => null, 'discount' => 0.0, 'error' => 'That coupon is outside its dates.', 'id' => null];
        }
        if ($subtotal < (float) $coupon->min_order) {
            return ['code' => null, 'discount' => 0.0, 'error' => 'The order is below the coupon minimum.', 'id' => null];
        }
        if ($coupon->usage_limit !== null && DB::table('coupon_redemptions')->where('coupon_id', $coupon->id)->count() >= $coupon->usage_limit) {
            return ['code' => null, 'discount' => 0.0, 'error' => 'That coupon has reached its limit.', 'id' => null];
        }
        if ($coupon->per_customer && auth()->check()) {
            $used = DB::table('coupon_redemptions')->where('coupon_id', $coupon->id)->where('user_id', auth()->id())->count();
            if ($used >= $coupon->per_customer) {
                return ['code' => null, 'discount' => 0.0, 'error' => 'You have already used that coupon.', 'id' => null];
            }
        }
        $eligible = $subtotal;
        if ($coupon->product_id || $coupon->category_id) {
            $eligible = (float) $items->filter(function ($item) use ($coupon) {
                return ($coupon->product_id && (int) $item->id === (int) $coupon->product_id)
                    || ($coupon->category_id && (int) $item->category_id === (int) $coupon->category_id);
            })->sum('line_total');
            if ($eligible <= 0) {
                return ['code' => null, 'discount' => 0.0, 'error' => 'That coupon does not apply to these products.', 'id' => null];
            }
        }

        return [
            'code' => $coupon->code,
            'id' => $coupon->id,
            'discount' => Money::discount($coupon->type, (float) $coupon->amount, $eligible, $coupon->max_discount !== null ? (float) $coupon->max_discount : null),
            'error' => null,
        ];
    }

    private static function tax($items, float $subtotal, float $discount, ?int $stateId): array
    {
        if ($subtotal <= 0) {
            return ['amount' => 0.0, 'label' => 'GST not configured'];
        }
        $business = config('shop.business_state_id') ? (int) config('shop.business_state_id') : null;
        $amount = 0.0;
        $labels = [];
        foreach ($items as $item) {
            $rate = $item->gst_rate === null ? null : (float) $item->gst_rate;
            $share = ($item->line_total / $subtotal) * $discount;
            $line = Money::tax(max(0, $item->line_total - $share), $rate, $stateId, $business);
            $amount += $line['amount'];
            $labels[$line['label']] = true;
        }
        $labels = array_keys($labels);

        return ['amount' => round($amount, 2), 'label' => count($labels) === 1 ? $labels[0] : 'GST'];
    }
}
