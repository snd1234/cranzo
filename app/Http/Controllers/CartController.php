<?php

namespace App\Http\Controllers;

use App\Services\CartRecord;
use App\Services\CheckoutQuote;
use App\Services\Inventory;
use App\Services\OrderNotice;
use App\Services\OrderWriter;
use App\Services\Payments\OnlinePayment;
use App\Support\CheckoutAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends BaseController
{
    public function index()
    {
        $quote = CheckoutQuote::summary();

        return view('cart', [
            'items' => $quote['items'],
            'quote' => $quote,
            'total' => $quote['total'],
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10',
            'color_id' => 'nullable|integer',
        ]);

        $product = $this->sellableProduct((int) $data['product_id']);
        $colorId = $data['color_id'] ?? null;
        $cart = session('shop_cart', []);
        $qty = min(10, ($cart[$product->id]['qty'] ?? 0) + (int) $data['quantity']);
        $this->assertStock($product->id, $qty, $colorId ? (int) $colorId : null);
        $cart[$product->id] = ['qty' => $qty, 'color_id' => $colorId ? (int) $colorId : null];
        session(['shop_cart' => $cart]);
        CartRecord::sync();

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

        $cart = session('shop_cart', []);
        $colorId = $cart[$data['product_id']]['color_id'] ?? null;
        $this->assertStock((int) $data['product_id'], (int) $data['quantity'], $colorId ? (int) $colorId : null);
        if (isset($cart[$data['product_id']])) {
            $cart[$data['product_id']]['qty'] = (int) $data['quantity'];
            session(['shop_cart' => $cart]);
            CartRecord::sync();
        }

        return back();
    }

    public function remove(int $id)
    {
        $cart = session('shop_cart', []);
        unset($cart[$id]);
        session(['shop_cart' => $cart]);
        CartRecord::sync();

        return back()->with('success', 'Item removed.');
    }

    public function checkout()
    {
        $items = $this->cartItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }
        $paid = app(OnlinePayment::class)->paidOrderFromSession();
        if ($paid) {
            CartRecord::converted();
            session()->forget(['shop_cart', 'shop_coupon', 'pending_online_order']);
            session(['last_order_number' => $paid->order_number]);

            return redirect()->route('checkout.complete', $paid->order_number);
        }

        $states = DB::table('state')->where('status', 1)->orderBy('state_name')->get();
        $addresses = auth()->check()
            ? DB::table('address')->where('user_id', auth()->id())->orderBy('default_type')->orderByDesc('id')->get()
            : collect();
        $addresses->each(function ($address) use ($states) {
            $address->state_name = optional($states->first(fn ($state) => (string) $state->id === (string) $address->state_id))->state_name;
        });

        $quote = CheckoutQuote::summary();

        return view('checkout', [
            'items' => $quote['items'],
            'quote' => $quote,
            'states' => $states,
            'addresses' => $addresses,
            'onlineEnabled' => app(OnlinePayment::class)->configured(),
        ]);
    }

    public function place(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cod'],
        ], [
            'payment_method.in' => 'Online payment is not available yet. Choose cash on delivery.',
        ]);

        $data = CheckoutAddress::fromRequest($request);

        $quote = CheckoutQuote::summary((int) $data['state_id']);
        $items = $quote['items'];
        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }
        if ($quote['coupon_error']) {
            return back()->withErrors(['coupon' => $quote['coupon_error']]);
        }

        try {
            $placed = OrderWriter::place($data, $quote, 2, true);
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['quantity' => 'Not enough stock to place this order.']);
        }
        $orderNumber = $placed['order_number'];

        OrderNotice::queue((int) $placed['order_id'], 'placed');
        CartRecord::converted();
        session()->forget('shop_cart');
        session()->forget('shop_coupon');
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

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:40']]);
        session(['shop_coupon' => strtoupper(trim($data['code']))]);
        $quote = CheckoutQuote::summary();
        if ($quote['coupon_error']) {
            session()->forget('shop_coupon');

            return back()->withErrors(['coupon' => $quote['coupon_error']]);
        }

        return back()->with('success', 'Coupon applied.');
    }

    public function removeCoupon()
    {
        session()->forget('shop_coupon');

        return back()->with('success', 'Coupon removed.');
    }

    private function cartItems()
    {
        return CheckoutQuote::items();
    }

    private function sellableProduct(int $id): object
    {
        $product = DB::table('product')->where('id', $id)->where('status', 1)->first();
        if (! $product) {
            throw ValidationException::withMessages(['product_id' => 'This product is not available.']);
        }

        return $product;
    }

    private function assertStock(int $productId, int $qty, ?int $colorId = null): void
    {
        $query = DB::table('product_details')->where('product_id', $productId);
        if ($colorId) {
            $query->where('color_id', $colorId);
        }
        $stock = (int) $query->sum('product_quantity');
        if ($stock < $qty) {
            throw ValidationException::withMessages(['quantity' => 'Only '.$stock.' units are available.']);
        }
    }
}
