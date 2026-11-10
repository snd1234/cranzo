<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartRecord
{
    public static function sync(): void
    {
        if (! Schema::hasTable('stored_carts')) {
            return;
        }
        $items = session('shop_cart', []);
        $sessionId = session()->getId();
        if ($sessionId === '') {
            return;
        }
        if ($items === []) {
            DB::table('stored_carts')->where('session_id', $sessionId)->whereNull('converted_at')->delete();

            return;
        }
        DB::table('stored_carts')->updateOrInsert(
            ['session_id' => $sessionId],
            [
                'user_id' => auth()->id(),
                'items' => json_encode($items),
                'converted_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public static function converted(): void
    {
        if (! Schema::hasTable('stored_carts') || session()->getId() === '') {
            return;
        }
        DB::table('stored_carts')->where('session_id', session()->getId())->update([
            'converted_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
