<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends BaseController
{
    public function index()
    {
        $items = $this->cartItems();

        return view('cart', [
            'items' => $items,
            'total' => $items->sum('line_total'),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $product = $this->sellableProduct((int) $data['product_id']);
        $cart = session('shop_cart', []);
        $qty = min(10, ($cart[$product->id]['qty'] ?? 0) + (int) $data['quantity']);
        $this->assertStock($product->id, $qty);
        $cart[$product->id] = ['qty' => $qty];
        session(['shop_cart' => $cart]);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return redirect()->route('cart.index')->with('success', 'Product added to your cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $this->assertStock((int) $data['product_id'], (int) $data['quantity']);
        $cart = session('shop_cart', []);
        if (isset($cart[$data['product_id']])) {
            $cart[$data['product_id']]['qty'] = (int) $data['quantity'];
            session(['shop_cart' => $cart]);
        }

        return back();
    }

    public function remove(int $id)
    {
        $cart = session('shop_cart', []);
        unset($cart[$id]);
        session(['shop_cart' => $cart]);

        return back()->with('success', 'Item removed.');
    }

    public function checkout()
    {
        $items = $this->cartItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $states = DB::table('state')->where('status', 1)->orderBy('state_name')->get();

        return view('checkout', [
            'items' => $items,
            'total' => $items->sum('line_total'),
            'states' => $states,
        ]);
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:128',
            'mobile_number' => 'required|string|max:15',
            'address' => 'required|string|max:256',
            'locality' => 'required|string|max:256',
            'city' => 'required|string|max:30',
            'state_id' => 'required|integer',
            'pincode' => 'required|digits:6',
        ]);

        $items = $this->cartItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        foreach ($items as $item) {
            $this->assertStock($item->id, $item->qty);
        }

        $orderNumber = now()->format('YmdHis').random_int(10, 99);

        DB::transaction(function () use ($data, $items, $orderNumber) {
            $addressId = ((int) DB::table('address')->lockForUpdate()->max('id')) + 1;
            DB::table('address')->insert([
                'id' => $addressId,
                'user_id' => 0,
                'name' => $data['name'],
                'mobile_number' => $data['mobile_number'],
                'address' => $data['address'],
                'pincode' => (int) $data['pincode'],
                'locality' => $data['locality'],
                'city' => $data['city'],
                'state_id' => (string) $data['state_id'],
                'address_type' => 1,
                'default_type' => 1,
                'created_date' => now(),
            ]);

            $orderId = DB::table('orders')->insertGetId([
                'session_id' => session()->getId(),
                'order_number' => $orderNumber,
                'user_id' => 0,
                'address_id' => $addressId,
                'order_status' => 1,
                'payment_status' => 1,
                'delivery_status' => 1,
                'booking_date' => now(),
                'booking_type' => 2,
                'delivery_date' => now()->addDays(5),
            ]);

            $detailId = (int) DB::table('order_details')->max('id');
            foreach ($items as $item) {
                $detailId++;
                DB::table('order_details')->insert([
                    'id' => $detailId,
                    'order_id' => $orderId,
                    'user_id' => 0,
                    'product_id' => $item->id,
                    'product_title' => $item->product_title,
                    'image' => $item->image_name ?: 'no-image.jpg',
                    'order_quantity' => $item->qty,
                    'amount_per_item' => (string) (int) $item->price,
                ]);

                $stockRow = DB::table('product_details')->where('product_id', $item->id)->orderBy('product_details_id')->first();
                if ($stockRow) {
                    DB::table('product_details')
                        ->where('product_details_id', $stockRow->product_details_id)
                        ->update(['product_quantity' => max(0, (int) $stockRow->product_quantity - $item->qty)]);
                }
            }

            $paymentId = ((int) DB::table('payments')->max('payment_id')) + 1;
            DB::table('payments')->insert([
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'total_amount' => (string) (int) $items->sum('line_total'),
                'amount_recieved' => '0',
                'discount_percent' => null,
                'payment_status' => 1,
                'created_date' => now(),
            ]);
        });

        session()->forget('shop_cart');
        session(['last_order_number' => $orderNumber]);

        return redirect()->route('checkout.complete', $orderNumber);
    }

    public function complete(string $orderNumber)
    {
        if (session('last_order_number') !== $orderNumber) {
            abort(404);
        }

        $order = DB::table('orders')->where('order_number', $orderNumber)->first();
        if (! $order) {
            abort(404);
        }

        $details = DB::table('order_details')->where('order_id', $order->id)->get();
        $payment = DB::table('payments')->where('order_id', $order->id)->first();

        return view('order_complete', compact('order', 'details', 'payment'));
    }

    private function cartItems()
    {
        $cart = session('shop_cart', []);
        if ($cart === []) {
            return collect();
        }

        $products = DB::table('product')
            ->whereIn('id', array_keys($cart))
            ->where('status', 1)
            ->get();

        $this->attachProductImages($products);
        $products = $products->keyBy('id');

        return collect($cart)->map(function ($row, $id) use ($products) {
            $product = $products->get((int) $id);
            if (! $product) {
                return null;
            }

            $image = $product->images->first();
            $product->qty = (int) $row['qty'];
            $product->price = (float) $product->discounted_price;
            $product->line_total = $product->price * $product->qty;
            $product->image_name = $image->image ?? $image->thumbnail ?? null;

            return $product;
        })->filter()->values();
    }

    private function sellableProduct(int $id): object
    {
        $product = DB::table('product')->where('id', $id)->where('status', 1)->first();
        if (! $product) {
            throw ValidationException::withMessages(['product_id' => 'This product is not available.']);
        }

        return $product;
    }

    private function assertStock(int $productId, int $qty): void
    {
        $stock = (int) DB::table('product_details')->where('product_id', $productId)->sum('product_quantity');
        if ($stock < $qty) {
            throw ValidationException::withMessages(['quantity' => 'Only '.$stock.' units are available.']);
        }
    }
}
