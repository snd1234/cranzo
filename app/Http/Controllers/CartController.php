<?php

namespace App\Http\Controllers;

use App\Services\CartRecord;
use App\Services\CheckoutQuote;
use App\Services\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        ]);
    }

    public function place(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cod'],
        ], [
            'payment_method.in' => 'Online payment is not available yet. Choose cash on delivery.',
        ]);

        if ($request->filled('address_id')) {
            abort_unless(auth()->check(), 403);
            $saved = DB::table('address')
                ->where('id', $request->integer('address_id'))
                ->where('user_id', auth()->id())
                ->first();
            if (! $saved) {
                throw ValidationException::withMessages(['address_id' => 'Choose one of your saved addresses.']);
            }
            $data = [
                'name' => trim(strip_tags((string) $saved->name)),
                'mobile_number' => preg_replace('/\s+/', '', (string) $saved->mobile_number) ?? '',
                'address' => trim(strip_tags((string) $saved->address)),
                'locality' => trim(strip_tags((string) $saved->locality)),
                'city' => trim(strip_tags((string) $saved->city)),
                'state_id' => $saved->state_id,
                'pincode' => (string) $saved->pincode,
            ];
            $check = validator($data, $this->addressRules(), $this->addressMessages());
            if ($check->fails()) {
                throw ValidationException::withMessages([
                    'address_id' => 'This saved address is incomplete. Choose another one or enter a new address.',
                ]);
            }
        } else {
            $request->merge([
                'name' => trim(strip_tags((string) $request->input('name'))),
                'mobile_number' => preg_replace('/\s+/', '', trim(strip_tags((string) $request->input('mobile_number')))) ?? '',
                'address' => trim(strip_tags((string) $request->input('address'))),
                'locality' => trim(strip_tags((string) $request->input('locality'))),
                'city' => trim(strip_tags((string) $request->input('city'))),
                'pincode' => trim(strip_tags((string) $request->input('pincode'))),
            ]);
            $data = $request->validate($this->addressRules(), $this->addressMessages());
        }

        $quote = CheckoutQuote::summary((int) $data['state_id']);
        $items = $quote['items'];
        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }
        if ($quote['coupon_error']) {
            return back()->withErrors(['coupon' => $quote['coupon_error']]);
        }

        $orderNumber = now()->format('YmdHis').random_int(10, 99);

        try {
        DB::transaction(function () use ($data, $items, $orderNumber, $quote) {
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
                'booking_type' => 2,
                'delivery_date' => now()->addDays(5),
            ]);

            $detailId = (int) DB::table('order_details')->max('id');
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

                Inventory::deduct((int) $item->id, $item->color_id ? (int) $item->color_id : null, (int) $item->qty, (int) $orderId);
            }

            $paymentId = ((int) DB::table('payments')->max('payment_id')) + 1;
            DB::table('payments')->insert([
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'total_amount' => (string) (int) round($quote['total']),
                'amount_recieved' => '0',
                'discount_percent' => null,
                'payment_status' => 1,
                'created_date' => now(),
            ]);
            if (\Illuminate\Support\Facades\Schema::hasTable('order_adjustments')) {
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
            if ($quote['coupon']) {
                $coupon = DB::table('coupons')->where('code', $quote['coupon'])->first();
                if ($coupon) {
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
        });
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['quantity' => 'Not enough stock to place this order.']);
        }

        $this->sendOrderMail($data['name'], $orderNumber, $quote['total']);
        CartRecord::converted();
        session()->forget('shop_cart');
        session()->forget('shop_coupon');
        session(['last_order_number' => $orderNumber]);

        return redirect()->route('checkout.complete', $orderNumber);
    }

    private function addressRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:128', 'regex:/^[\p{L}\s.\'-]{2,128}$/u'],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'address' => ['required', 'string', 'max:256'],
            'locality' => ['required', 'string', 'max:256'],
            'city' => ['required', 'string', 'max:30', 'regex:/^[\p{L}\s.\'-]{2,30}$/u'],
            'state_id' => ['required', 'integer', \Illuminate\Validation\Rule::exists('state', 'id')->where('status', 1)],
            'pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
        ];
    }

    private function addressMessages(): array
    {
        return [
            'name.regex' => 'Enter the recipient name using letters only.',
            'mobile_number.regex' => 'Enter a 10-digit Indian mobile number.',
            'city.regex' => 'Enter a city name using letters only.',
            'state_id.exists' => 'Select a state from the list.',
            'pincode.regex' => 'Enter a 6-digit PIN code.',
        ];
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

    private function sendOrderMail(string $name, string $orderNumber, float $total): void
    {
        $email = auth()->user()->email ?? null;
        if (! $email) {
            return;
        }
        try {
            Mail::raw($name.', your Cranzo order '.$orderNumber.' is placed. Total ₹'.number_format($total, 2).'. Payment is cash on delivery.', function ($message) use ($email, $orderNumber) {
                $message->to($email)->subject('Order '.$orderNumber.' placed');
            });
        } catch (Throwable $exception) {
            report($exception);
        }
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
