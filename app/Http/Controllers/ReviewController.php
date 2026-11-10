<?php

namespace App\Http\Controllers;

use App\Support\HtmlCleaner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends BaseController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_title' => ['required', 'string', 'max:120'],
            'comments' => ['required', 'string', 'max:2000'],
        ]);
        $product = DB::table('product')->where('id', $data['product_id'])->where('status', 1)->first();
        abort_unless($product, 404);
        $purchased = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.product_id', $product->id)
            ->where('orders.user_id', Auth::id())
            ->whereNull('orders.cancelled_at')
            ->exists();
        if (! $purchased) {
            return back()->withErrors(['review' => 'You can review a product after you buy it.']);
        }
        $exists = DB::table('reviews')->where('product_id', $product->id)->where('user_id', Auth::id())->exists();
        if ($exists) {
            return back()->withErrors(['review' => 'You have already reviewed this product.']);
        }
        DB::table('reviews')->insert([
            'review_id' => ((int) DB::table('reviews')->max('review_id')) + 1,
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $data['rating'],
            'review_title' => $data['review_title'],
            'comments' => HtmlCleaner::clean($data['comments']),
            'status' => 0,
            'created_date' => now(),
        ]);

        return back()->with('success', 'Review submitted and waiting for approval.');
    }
}
