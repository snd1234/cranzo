<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Inventory
{
    public static function deduct(int $productId, ?int $colorId, int $qty, int $orderId): void
    {
        $query = DB::table('product_details')->where('product_id', $productId)->orderBy('product_details_id')->lockForUpdate();
        if ($colorId) {
            $query->where('color_id', $colorId);
        }
        $row = $query->first();
        if (! $row || (int) $row->product_quantity < $qty) {
            throw new \RuntimeException('Not enough stock.');
        }
        DB::table('product_details')->where('product_details_id', $row->product_details_id)->update([
            'product_quantity' => (int) $row->product_quantity - $qty,
        ]);
        self::move($productId, $colorId, -$qty, 'order', $orderId);
    }

    public static function restore(int $productId, int $qty, int $orderId): void
    {
        $row = DB::table('product_details')->where('product_id', $productId)->orderBy('product_details_id')->lockForUpdate()->first();
        if (! $row) {
            return;
        }
        DB::table('product_details')->where('product_details_id', $row->product_details_id)->update([
            'product_quantity' => (int) $row->product_quantity + $qty,
        ]);
        self::move($productId, null, $qty, 'cancel', $orderId);
    }

    public static function alreadyDeducted(int $orderId): bool
    {
        return Schema::hasTable('stock_movements')
            && DB::table('stock_movements')->where('order_id', $orderId)->where('reason', 'order')->exists();
    }

    private static function move(int $productId, ?int $colorId, int $qty, string $reason, int $orderId): void
    {
        if (! Schema::hasTable('stock_movements')) {
            return;
        }
        DB::table('stock_movements')->insert([
            'product_id' => $productId,
            'color_id' => $colorId,
            'quantity_change' => $qty,
            'reason' => $reason,
            'order_id' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
