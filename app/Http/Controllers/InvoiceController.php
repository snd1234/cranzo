<?php

namespace App\Http\Controllers;

use App\Support\InvoicePdf;
use App\Support\OrderLabel;
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
        $state = $address ? DB::table('state')->where('id', $address->state_id)->value('state_name') : null;

        $lines = [];
        $computed = 0.0;
        foreach ($details as $line) {
            $rate = (float) $line->amount_per_item;
            $amount = $rate * (int) $line->order_quantity;
            $computed += $amount;
            $lines[] = [
                'title' => (string) $line->product_title,
                'qty' => (int) $line->order_quantity,
                'rate' => $rate,
                'amount' => $amount,
            ];
        }

        $bill = ['Customer'];
        if ($address) {
            $bill = array_values(array_filter([
                (string) $address->name,
                trim((string) $address->address.($address->locality ? ', '.$address->locality : '')),
                trim((string) $address->city.($state ? ', '.$state : '').' '.$address->pincode),
                $address->mobile_number ? (string) $address->mobile_number : null,
            ]));
        }

        $payment = (int) $row->booking_type === 2 ? 'Cash on delivery' : 'As recorded';
        if ((int) $row->payment_status === 2) {
            $payment = 'COD received';
        } elseif ((int) $row->payment_status === 1 && (int) $row->booking_type === 2) {
            $payment = 'COD pending';
        }

        $pdf = InvoicePdf::make([
            'number' => (string) $row->order_number,
            'date' => $row->booking_date ? \Illuminate\Support\Carbon::parse($row->booking_date)->format('d M Y') : '',
            'payment' => $payment,
            'status' => OrderLabel::summary($row),
            'bill_to' => $bill,
            'lines' => $lines,
            'subtotal' => $adjust->subtotal ?? $computed,
            'discount' => $adjust->discount ?? 0,
            'tax_label' => $adjust->tax_label ?? 'GST not configured',
            'tax' => $adjust->tax ?? 0,
            'total' => $adjust->total ?? $computed,
            'notes' => [
                'Amounts are the prices stored on this order.',
                'A GST identification number is not printed because it has not been configured.',
            ],
        ], public_path('front/images/cranzo-logo.jpg'));

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-'.$row->order_number.'.pdf"',
        ]);
    }
}
