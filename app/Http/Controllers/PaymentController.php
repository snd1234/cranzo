<?php

namespace App\Http\Controllers;

use App\Services\CartRecord;
use App\Services\Payments\OnlinePayment;
use App\Services\Payments\PaymentException;
use App\Support\CheckoutAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends BaseController
{
    public function start(Request $request, OnlinePayment $payments)
    {
        $request->validate(['payment_method' => ['required', 'in:online']]);
        try {
            $address = CheckoutAddress::fromRequest($request);
            $payload = $payments->start($address);
        } catch (PaymentException $exception) {
            throw ValidationException::withMessages(['payment_method' => $exception->getMessage()]);
        } catch (\RuntimeException $exception) {
            throw ValidationException::withMessages(['quantity' => 'Not enough stock to place this order.']);
        }

        return response()->json($payload);
    }

    public function verify(Request $request, OnlinePayment $payments)
    {
        $data = $request->validate([
            'order_number' => ['required', 'string', 'max:100'],
            'razorpay_order_id' => ['required', 'string', 'max:64'],
            'razorpay_payment_id' => ['required', 'string', 'max:64'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        try {
            $order = $payments->verify(
                $data['order_number'],
                $data['razorpay_order_id'],
                $data['razorpay_payment_id'],
                $data['razorpay_signature']
            );
        } catch (PaymentException $exception) {
            throw ValidationException::withMessages(['payment_method' => $exception->getMessage()]);
        }

        CartRecord::converted();
        session()->forget(['shop_cart', 'shop_coupon', 'pending_online_order']);
        session(['last_order_number' => $order->order_number]);

        return response()->json([
            'redirect' => route('checkout.complete', $order->order_number),
        ]);
    }

    public function cancel(Request $request, OnlinePayment $payments)
    {
        $data = $request->validate(['order_number' => ['required', 'string', 'max:100']]);
        try {
            $payments->cancel($data['order_number']);
        } catch (PaymentException $exception) {
            throw ValidationException::withMessages(['payment_method' => $exception->getMessage()]);
        }

        return response()->json(['status' => 'cancelled']);
    }

    public function webhook(Request $request, OnlinePayment $payments)
    {
        try {
            $payments->handleWebhook(
                $request->getContent(),
                (string) $request->header('X-Razorpay-Signature', ''),
                (string) $request->header('X-Razorpay-Event-Id', '')
            );
        } catch (PaymentException $exception) {
            return response()->json(['message' => 'Payment could not be confirmed.'], 400);
        }

        return response()->noContent();
    }
}
