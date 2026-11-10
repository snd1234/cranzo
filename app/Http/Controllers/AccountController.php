<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/', 'unique:users,mobile_number,'.$user->id],
        ]);

        $emailChanged = $data['email'] !== $user->email;
        $user->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['email'],
            'mobile_number' => $data['mobile_number'],
        ]);
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')->with('success', 'Confirm the new email address before using the account again.');
        }

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $request->user()->update(['password' => $data['password']]);

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
}
