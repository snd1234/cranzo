<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnController extends BaseController
{
    public function store(Request $request, int $order)
    {
        $row = DB::table('orders')->where('id', $order)->where('user_id', Auth::id())->whereNull('cancelled_at')->first();
        abort_unless($row, 404);
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:80'],
            'details' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        $bought = (int) DB::table('order_details')->where('order_id', $row->id)->where('product_id', $data['product_id'])->sum('order_quantity');
        $open = (int) DB::table('return_requests')
            ->where('order_id', $row->id)
            ->where('product_id', $data['product_id'])
            ->whereNotIn('status', ['rejected'])
            ->sum('quantity');
        if ($bought < 1 || $data['quantity'] + $open > $bought) {
            return back()->withErrors(['quantity' => 'That quantity is more than what this order can return.']);
        }
        DB::table('return_requests')->insert([
            'order_id' => $row->id,
            'user_id' => Auth::id(),
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'image' => $request->hasFile('image') ? $this->storeImage($request->file('image')) : null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Return request sent. It is not a refund until the shop confirms it.');
    }

    public function mine()
    {
        $requests = DB::table('return_requests')->where('user_id', Auth::id())->orderByDesc('id')->get();

        return view('account.returns', compact('requests'));
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('uploads/returns');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $name = 'return-'.time().'-'.Str::lower(Str::random(6)).'.'.strtolower($file->extension() ?: 'jpg');
        $file->move($directory, $name);

        return $name;
    }
}
