<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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

        $emailChanged = strcasecmp((string) $user->email, $data['email']) !== 0;
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
            app(\App\Services\Notifier::class)->userCreated($user);

            return redirect()->route('verification.notice')->with('success', 'Profile updated. Confirm the new email address.');
        }

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

    public function payments()
    {
        return view('account.payments', [
            'payments' => $this->paymentHistory(),
        ]);
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

            $bookingType = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'booking_type') ? (int) $locked->booking_type : 2;
            $deducted = Inventory::alreadyDeducted((int) $locked->id);
            if ($bookingType !== 1 || $deducted) {
                foreach (DB::table('order_details')->where('order_id', $row->id)->get() as $line) {
                    Inventory::restore((int) $line->product_id, (int) $line->order_quantity, (int) $row->id);
                }
            }

            DB::table('orders')->where('id', $row->id)->update(['cancelled_at' => now()]);
        });

        return back()->with('success', 'The order has been cancelled.');
    }

    public function retryPayment(int $order)
    {
        $row = $this->ownOrder($order);
        $bookingType = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'booking_type') ? (int) $row->booking_type : 0;
        if ($row->cancelled_at || (int) $row->payment_status !== 1 || $bookingType !== 1) {
            return back()->withErrors(['order' => 'This order cannot be paid again.']);
        }

        $cart = [];
        foreach (DB::table('order_details')->where('order_id', $row->id)->get() as $line) {
            $product = DB::table('product')->where('id', $line->product_id)->where('status', 1)->first();
            if (! $product) {
                continue;
            }
            $stock = (int) DB::table('product_details')->where('product_id', $line->product_id)->sum('product_quantity');
            if ($stock < 1) {
                continue;
            }
            $cart[$line->product_id] = ['qty' => min(10, (int) $line->order_quantity, $stock)];
        }
        if ($cart === []) {
            return back()->withErrors(['order' => 'Those products are no longer available.']);
        }

        session([
            'shop_cart' => $cart,
            'pending_online_order' => $row->order_number,
        ]);

        return redirect()->route('checkout')->with('success', 'Review the order and choose online payment to try again.');
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

    private function paymentHistory()
    {
        if (! Schema::hasTable('payments') || ! Schema::hasTable('orders')) {
            return collect();
        }

        $columns = [
            'payments.payment_id',
            'payments.order_id',
            'payments.total_amount',
            'payments.payment_status',
            'orders.order_number',
            'orders.payment_status as order_payment_status',
        ];
        foreach (['amount', 'currency', 'payment_gateway', 'payment_state', 'gateway_transaction_id', 'created_date'] as $column) {
            if (Schema::hasColumn('payments', $column)) {
                $columns[] = 'payments.'.$column;
            }
        }
        if (Schema::hasColumn('orders', 'booking_type')) {
            $columns[] = 'orders.booking_type';
        }
        if (Schema::hasColumn('orders', 'booking_date')) {
            $columns[] = 'orders.booking_date';
        }

        return DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->where('orders.user_id', Auth::id())
            ->orderByDesc('payments.payment_id')
            ->limit(20)
            ->get($columns)
            ->map(fn ($row) => $this->presentPayment($row));
    }

    private function presentPayment(object $row): array
    {
        $type = (int) ($this->field($row, 'booking_type') ?? 0);
        $paid = (int) ($this->field($row, 'order_payment_status') ?? $row->payment_status) === 2;
        $state = (string) ($this->field($row, 'payment_state') ?? '');
        $gateway = (string) ($this->field($row, 'payment_gateway') ?? '');
        $amount = $this->field($row, 'amount');
        $amount = $amount !== null && $amount !== '' ? (float) $amount : (float) $row->total_amount;
        $when = $this->field($row, 'created_date') ?: $this->field($row, 'booking_date');

        if ($state === 'refunded') {
            $status = 'Refunded';
        } elseif ($state === 'failed') {
            $status = 'Unsuccessful';
        } elseif ($type === 1 || $gateway === 'razorpay') {
            $status = $paid ? 'Paid online' : 'Online payment pending';
        } elseif ($type === 2 || $gateway === 'cod') {
            $status = $paid ? 'COD received' : 'Cash on delivery pending';
        } else {
            $status = $paid ? 'Paid' : 'Pending';
        }

        if ($type === 2 || $gateway === 'cod') {
            $method = 'Cash on delivery';
        } elseif ($type === 1 || $gateway === 'razorpay') {
            $method = 'Razorpay';
        } else {
            $method = 'As recorded';
        }

        return [
            'order_id' => (int) $row->order_id,
            'order_number' => (string) $row->order_number,
            'when' => $when ? \Illuminate\Support\Carbon::parse($when)->format('d M Y, H:i') : '',
            'method' => $method,
            'status' => $status,
            'amount' => '₹'.number_format($amount, 2),
            'reference' => $this->field($row, 'gateway_transaction_id') ?: null,
        ];
    }

    private function field(object $row, string $key): mixed
    {
        return property_exists($row, $key) ? $row->$key : null;
    }

    private function plain(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }
}
