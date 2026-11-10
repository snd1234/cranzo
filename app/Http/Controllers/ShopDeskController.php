<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopDeskController extends Controller
{
    public function coupons()
    {
        $coupons = DB::table('coupons')->orderByDesc('id')->paginate(20);

        return view('admin.shop.coupons', compact('coupons'));
    }

    public function storeCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'unique:coupons,code'],
            'type' => ['required', 'in:percent,fixed'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_customer' => ['nullable', 'integer', 'min:1'],
        ]);
        $data['code'] = strtoupper($data['code']);
        $data['min_order'] = $data['min_order'] ?? 0;
        $data['status'] = 1;
        $data['created_at'] = now();
        $data['updated_at'] = now();
        DB::table('coupons')->insert($data);

        return back()->with('success', 'Coupon saved. It is not applied until a customer enters it.');
    }

    public function couponStatus(int $coupon)
    {
        $row = DB::table('coupons')->where('id', $coupon)->first();
        abort_unless($row, 404);
        DB::table('coupons')->where('id', $coupon)->update(['status' => $row->status ? 0 : 1, 'updated_at' => now()]);

        return back()->with('success', 'Coupon updated.');
    }

    public function reports(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $orders = DB::table('orders')->whereNull('cancelled_at')
            ->when($from, fn ($query) => $query->whereDate('booking_date', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('booking_date', '<=', $to));
        $count = (clone $orders)->count();
        $ids = (clone $orders)->pluck('id');
        $gross = $ids->isEmpty() ? 0 : (float) DB::table('order_adjustments')->whereIn('order_id', $ids)->sum('total');
        $best = DB::table('order_details')->whereIn('order_id', $ids->isEmpty() ? [0] : $ids)
            ->select('product_title', DB::raw('SUM(order_quantity) as sold'))
            ->groupBy('product_title')->orderByDesc('sold')->limit(8)->get();

        return view('admin.shop.reports', [
            'count' => $count,
            'gross' => $gross,
            'best' => $best,
            'customers' => DB::table('users')->count(),
        ]);
    }

    public function exportOrders()
    {
        $rows = DB::table('orders')->orderByDesc('id')->limit(500)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['order_number', 'user_id', 'order_status', 'payment_status', 'delivery_status', 'booking_date']);
            foreach ($rows as $row) {
                fputcsv($out, [$row->order_number, $row->user_id, $row->order_status, $row->payment_status, $row->delivery_status, $row->booking_date]);
            }
            fclose($out);
        }, 'orders.csv');
    }

    public function stock()
    {
        $threshold = (int) config('shop.low_stock');
        $rows = DB::table('product')->where('status', 1)->get(['id', 'product_title'])->map(function ($product) {
            $product->stock = (int) DB::table('product_details')->where('product_id', $product->id)->sum('product_quantity');

            return $product;
        })->filter(fn ($product) => $product->stock <= $threshold)->sortBy('stock')->take(50)->values();
        $moves = DB::table('stock_movements')->orderByDesc('id')->limit(30)->get();

        return view('admin.shop.stock', compact('rows', 'moves', 'threshold'));
    }

    public function contacts(Request $request)
    {
        $messages = DB::table('contact_us')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('subject', 'like', $term);
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderByDesc('id')->paginate(20)->withQueryString();
        $unread = DB::table('contact_us')->where('status', 'new')->count();

        return view('admin.shop.contacts', compact('messages', 'unread'));
    }

    public function updateContact(Request $request, int $message)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,closed'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);
        DB::table('contact_us')->where('id', $message)->update($data);

        return back()->with('success', 'Message updated.');
    }

    public function returns()
    {
        $requests = DB::table('return_requests')->orderByDesc('id')->paginate(20);

        return view('admin.shop.returns', compact('requests'));
    }

    public function updateReturn(Request $request, int $return)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,received,refund_recorded'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);
        DB::table('return_requests')->where('id', $return)->update($data + ['updated_at' => now()]);

        return back()->with('success', 'Return request updated. No payment was sent.');
    }

    public function reviews()
    {
        $reviews = DB::table('reviews')->orderByDesc('review_id')->paginate(20);

        return view('admin.shop.reviews', compact('reviews'));
    }

    public function reviewStatus(int $review)
    {
        $row = DB::table('reviews')->where('review_id', $review)->first();
        abort_unless($row, 404);
        DB::table('reviews')->where('review_id', $review)->update(['status' => (int) $row->status === 1 ? 0 : 1]);

        return back()->with('success', 'Review updated.');
    }

    public function carts()
    {
        $cutoff = now()->subHours((int) config('shop.abandoned_hours'));
        $carts = DB::table('stored_carts')->whereNull('converted_at')->where('updated_at', '<', $cutoff)->orderByDesc('updated_at')->paginate(20);

        return view('admin.shop.carts', compact('carts'));
    }

    public function recoverCart(int $cart)
    {
        DB::table('stored_carts')->where('id', $cart)->update(['recovered_at' => now(), 'updated_at' => now()]);

        return back()->with('success', 'Marked as reviewed. No message was sent.');
    }
}
