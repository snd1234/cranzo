<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderDeskController extends Controller
{
    public function index(Request $request)
    {
        $orders = DB::table('orders')
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->select('orders.*', 'users.email', 'users.first_name', 'users.mobile_number')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('orders.order_number', 'like', $term)
                        ->orWhere('users.email', 'like', $term)
                        ->orWhere('users.first_name', 'like', $term)
                        ->orWhere('users.mobile_number', 'like', $term);
                });
            })
            ->when($request->filled('order_status'), fn ($query) => $query->where('orders.order_status', (int) $request->input('order_status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('orders.payment_status', (int) $request->input('payment_status')))
            ->when($request->filled('delivery_status'), fn ($query) => $query->where('orders.delivery_status', (int) $request->input('delivery_status')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('orders.booking_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('orders.booking_date', '<=', $request->input('to')))
            ->orderByDesc('orders.id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $order)
    {
        $row = DB::table('orders')->where('id', $order)->first();
        abort_unless($row, 404);
        $details = DB::table('order_details')->where('order_id', $order)->get();
        $address = DB::table('address')->where('id', $row->address_id)->first();
        $events = DB::table('order_events')->where('order_id', $order)->orderByDesc('id')->get();
        $adjust = DB::table('order_adjustments')->where('order_id', $order)->first();

        return view('admin.orders.show', compact('row', 'details', 'address', 'events', 'adjust'));
    }

    public function update(Request $request, int $order)
    {
        $row = DB::table('orders')->where('id', $order)->first();
        abort_unless($row, 404);
        $data = $request->validate([
            'order_status' => ['required', 'integer', 'in:1,2,3'],
            'delivery_status' => ['required', 'integer', 'in:1,2,3'],
            'payment_status' => ['required', 'integer', 'in:1,2'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        if ((int) $data['payment_status'] === 2 && (int) $row->booking_type !== 2) {
            return back()->withErrors(['payment_status' => 'Only a cash-on-delivery order can be marked received here.']);
        }
        foreach (['order_status', 'delivery_status', 'payment_status'] as $field) {
            if ((string) $row->{$field} !== (string) $data[$field]) {
                DB::table('order_events')->insert([
                    'order_id' => $order,
                    'field' => $field,
                    'from_value' => (string) $row->{$field},
                    'to_value' => (string) $data[$field],
                    'note' => $data['note'] ?? null,
                    'admin_id' => Auth::guard('admin')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        DB::table('orders')->where('id', $order)->update([
            'order_status' => $data['order_status'],
            'delivery_status' => $data['delivery_status'],
            'payment_status' => $data['payment_status'],
        ]);

        return back()->with('success', 'Order updated.');
    }
}
