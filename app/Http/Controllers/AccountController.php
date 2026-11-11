<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AccountController extends BaseController
{
    public function dashboard()
    {
        $userId = (int) Auth::id();
        $orders = DB::table('orders')->where('user_id', $userId);
        $recent = (clone $orders)->orderByDesc('id')->limit(5)->get();

        return view('account.dashboard', [
            'orderCount' => (clone $orders)->count(),
            'pendingCount' => (clone $orders)->whereNull('cancelled_at')->where('delivery_status', 1)->where('order_status', 1)->count(),
            'addressCount' => DB::table('address')->where('user_id', $userId)->count(),
            'recent' => $recent,
        ]);
    }

    public function editProfile()
    {
        return view('account.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->merge([
            'first_name' => $this->plain($request->input('first_name')),
            'last_name' => $this->plain($request->input('last_name')),
            'email' => strtolower($this->plain($request->input('email'))),
            'mobile_number' => preg_replace('/\s+/', '', $this->plain($request->input('mobile_number'))) ?? '',
        ]);
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80', 'regex:/^[\p{L}\s.\'-]{1,80}$/u'],
            'last_name' => ['required', 'string', 'max:80', 'regex:/^[\p{L}\s.\'-]{1,80}$/u'],
            'email' => ['required', 'email:filter', 'max:255', Rule::unique('users', 'email')->ignore($user->id), Rule::unique('users', 'username')->ignore($user->id)],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/', Rule::unique('users', 'mobile_number')->ignore($user->id)],
        ], [
            'first_name.regex' => 'Enter a first name using letters only.',
            'last_name.regex' => 'Enter a last name using letters only.',
            'mobile_number.regex' => 'Enter a 10-digit Indian mobile number.',
            'mobile_number.unique' => 'That mobile number is already used by another account.',
        ]);

        $user->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['email'],
            'mobile_number' => $data['mobile_number'],
        ]);
        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->max(72)->letters()->numbers(), function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                if (Hash::check((string) $value, (string) $request->user()->password)) {
                    $fail('Choose a password that is different from your current password.');
                }
            }],
        ], [
            'password.min' => 'Use at least 8 characters, including a letter and a number.',
            'password.letters' => 'Use at least 8 characters, including a letter and a number.',
            'password.numbers' => 'Use at least 8 characters, including a letter and a number.',
        ]);

        $request->user()->forceFill([
            'password' => $data['password'],
            'remember_token' => Str::random(60),
        ])->save();
        $request->session()->regenerate();

        return back()->with('success', 'Password updated.');
    }

    public function orders()
    {
        $orders = DB::table('orders')->where('user_id', Auth::id())->orderByDesc('id')->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function showOrder(int $order)
    {
        $row = $this->ownOrder($order);
        $details = DB::table('order_details')->where('order_id', $row->id)->get();
        $address = DB::table('address')->where('id', $row->address_id)->where('user_id', Auth::id())->first();
        $state = $address ? DB::table('state')->where('id', $address->state_id)->value('state_name') : null;

        return view('account.order', compact('row', 'details', 'address', 'state'));
    }

    public function cancelOrder(int $order)
    {
        $row = $this->ownOrder($order);
        if ($row->cancelled_at || (int) $row->order_status !== 1 || (int) $row->payment_status !== 1 || (int) $row->delivery_status !== 1) {
            return back()->withErrors(['order' => 'This order can no longer be cancelled.']);
        }

        DB::transaction(function () use ($row) {
            $locked = DB::table('orders')->where('id', $row->id)->lockForUpdate()->first();
            if (! $locked || $locked->cancelled_at) {
                return;
            }

            foreach (DB::table('order_details')->where('order_id', $row->id)->get() as $line) {
                Inventory::restore((int) $line->product_id, (int) $line->order_quantity, (int) $row->id);
            }

            DB::table('orders')->where('id', $row->id)->update(['cancelled_at' => now()]);
        });

        return back()->with('success', 'The order has been cancelled.');
    }

    public function reorder(int $order)
    {
        $row = $this->ownOrder($order);
        $cart = session('shop_cart', []);
        foreach (DB::table('order_details')->where('order_id', $row->id)->get() as $line) {
            $product = DB::table('product')->where('id', $line->product_id)->where('status', 1)->first();
            if (! $product) {
                continue;
            }
            $stock = (int) DB::table('product_details')->where('product_id', $line->product_id)->sum('product_quantity');
            $qty = min(10, (int) ($cart[$line->product_id]['qty'] ?? 0) + (int) $line->order_quantity);
            if ($stock < 1) {
                continue;
            }
            $cart[$line->product_id] = ['qty' => min($qty, $stock)];
        }
        session(['shop_cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Available items from that order are in your cart.');
    }

    private function ownOrder(int $id): object
    {
        $row = DB::table('orders')->where('id', $id)->where('user_id', Auth::id())->first();
        abort_if(! $row, 404);

        return $row;
    }

    private function plain(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }
}
