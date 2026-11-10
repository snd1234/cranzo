<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ShopCart
{
    public static function sanitize(): void
    {
        $cart = session('shop_cart', []);
        $clean = [];

        foreach ($cart as $id => $row) {
            $productId = (int) $id;
            $product = DB::table('product')->where('id', $productId)->where('status', 1)->first();
            if (! $product) {
                continue;
            }

            $stock = (int) DB::table('product_details')->where('product_id', $productId)->sum('product_quantity');
            $qty = min(10, max(1, (int) ($row['qty'] ?? 1)));
            if ($stock < 1) {
                continue;
            }

            $clean[$productId] = ['qty' => min($qty, $stock)];
        }

        session(['shop_cart' => $clean]);
    }
}
