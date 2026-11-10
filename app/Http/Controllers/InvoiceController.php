<?php

namespace App\Http\Controllers;

use App\Support\SimplePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoiceController extends BaseController
{
    public function customer(int $order)
    {
        $row = DB::table('orders')->where('id', $order)->where('user_id', Auth::id())->first();
        abort_unless($row, 404);

        return $this->pdf($row);
    }

    public function admin(int $order)
    {
        $row = DB::table('orders')->where('id', $order)->first();
        abort_unless($row, 404);

        return $this->pdf($row);
    }

    private function pdf(object $row)
    {
        $details = DB::table('order_details')->where('order_id', $row->id)->get();
        $address = DB::table('address')->where('id', $row->address_id)->first();
        $adjust = Schema::hasTable('order_adjustments')
            ? DB::table('order_adjustments')->where('order_id', $row->id)->first()
            : null;
        $lines = [
            'Cranzo invoice '.$row->order_number,
            'Invoice date: '.$row->booking_date,
            'Payment: cash on delivery',
            $address ? 'Ship to: '.$address->name.', '.$address->address.', '.$address->city.' '.$address->pincode : 'Address on order',
            ' ',
        ];
        foreach ($details as $line) {
            $lines[] = $line->product_title.' x '.$line->order_quantity.'  Rs '.$line->amount_per_item;
        }
        $lines[] = ' ';
        $lines[] = 'Subtotal: Rs '.($adjust->subtotal ?? 'see lines');
        $lines[] = 'Discount: Rs '.($adjust->discount ?? '0');
        $lines[] = ($adjust->tax_label ?? 'GST not stored').': Rs '.($adjust->tax ?? '0');
        $lines[] = 'Total: Rs '.($adjust->total ?? '');
        $lines[] = 'This invoice uses the prices stored on the order.';

        return response(SimplePdf::make($lines), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-'.$row->order_number.'.pdf"',
        ]);
    }
}
