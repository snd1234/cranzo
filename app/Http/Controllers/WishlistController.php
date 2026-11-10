<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WishlistController extends BaseController
{
    public function index()
    {
        $ids = $this->ids();
        $products = $ids === []
            ? collect()
            : DB::table('product')->whereIn('id', $ids)->where('status', 1)->get();
        $this->attachProductImages($products);

        return view('account.wishlist', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['product_id' => ['required', 'integer']]);
        $product = DB::table('product')->where('id', $data['product_id'])->where('status', 1)->first();
        abort_unless($product, 404);
        if ($this->ids() && in_array((int) $product->id, $this->ids(), true)) {
            return back()->with('success', 'Already in your wishlist.');
        }
        DB::table('wishlists')->insert([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
            'product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Saved to your wishlist.');
    }

    public function destroy(int $product)
    {
        $this->query()->where('product_id', $product)->delete();

        return back()->with('success', 'Removed from your wishlist.');
    }

    public function move(int $product)
    {
        $row = $this->query()->where('product_id', $product)->first();
        abort_unless($row, 404);
        $cart = session('shop_cart', []);
        $cart[$product] = ['qty' => min(10, ($cart[$product]['qty'] ?? 0) + 1), 'color_id' => $cart[$product]['color_id'] ?? null];
        session(['shop_cart' => $cart]);
        $this->query()->where('product_id', $product)->delete();

        return redirect()->route('cart.index')->with('success', 'Moved to your cart.');
    }

    public static function claim(string $sessionId, int $userId): void
    {
        if (! Schema::hasTable('wishlists') || $sessionId === '') {
            return;
        }
        $existing = DB::table('wishlists')->where('user_id', $userId)->pluck('product_id')->all();
        DB::table('wishlists')->where('session_id', $sessionId)->whereNull('user_id')->whereIn('product_id', $existing)->delete();
        DB::table('wishlists')->where('session_id', $sessionId)->whereNull('user_id')->update([
            'user_id' => $userId,
            'session_id' => null,
            'updated_at' => now(),
        ]);
    }

    public static function countForView(): int
    {
        if (! Schema::hasTable('wishlists')) {
            return 0;
        }
        $query = DB::table('wishlists');
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        return $query->count();
    }

    private function ids(): array
    {
        return $this->query()->pluck('product_id')->map(fn ($id) => (int) $id)->all();
    }

    private function query()
    {
        $query = DB::table('wishlists');
        if (Auth::check()) {
            return $query->where('user_id', Auth::id());
        }

        return $query->where('session_id', session()->getId());
    }
}
