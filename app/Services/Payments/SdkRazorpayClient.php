<?php

namespace App\Services\Payments;

use Razorpay\Api\Api;

class SdkRazorpayClient implements RazorpayClient
{
    public function createOrder(int $amountPaise, string $receipt, array $notes): array
    {
        $order = $this->api()->order->create([
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
            'notes' => $notes,
        ]);

        return $this->orderPayload($order);
    }

    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): void
    {
        try {
            $this->api()->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);
        } catch (\Throwable $exception) {
            throw new PaymentException('Payment could not be confirmed.');
        }
    }

    public function verifyWebhookSignature(string $body, string $signature): void
    {
        $secret = (string) config('razorpay.webhook_secret');
        if ($secret === '') {
            throw new PaymentException('Online payment is not configured.');
        }

        try {
            $this->api()->utility->verifyWebhookSignature($body, $signature, $secret);
        } catch (\Throwable $exception) {
            throw new PaymentException('Payment could not be confirmed.');
        }
    }

    public function fetchPayment(string $paymentId): array
    {
        return $this->paymentPayload($this->api()->payment->fetch($paymentId));
    }

    public function capture(string $paymentId, int $amountPaise): array
    {
        $payment = $this->api()->payment->fetch($paymentId)->capture([
            'amount' => $amountPaise,
            'currency' => 'INR',
        ]);

        return $this->paymentPayload($payment);
    }

    public function refund(string $paymentId, int $amountPaise): array
    {
        $refund = $this->api()->payment->fetch($paymentId)->refund([
            'amount' => $amountPaise,
        ]);

        return [
            'id' => (string) ($refund['id'] ?? ''),
            'payment_id' => $paymentId,
            'amount' => (int) ($refund['amount'] ?? $amountPaise),
        ];
    }

    private function api(): Api
    {
        $key = (string) config('razorpay.key_id');
        $secret = (string) config('razorpay.key_secret');
        if ($key === '' || $secret === '') {
            throw new PaymentException('Online payment is not configured.');
        }

        return new Api($key, $secret);
    }

    private function orderPayload(mixed $order): array
    {
        return [
            'id' => (string) $order['id'],
            'amount' => (int) $order['amount'],
            'currency' => (string) $order['currency'],
        ];
    }

    private function paymentPayload(mixed $payment): array
    {
        return [
            'id' => (string) $payment['id'],
            'order_id' => (string) $payment['order_id'],
            'amount' => (int) $payment['amount'],
            'currency' => (string) $payment['currency'],
            'status' => (string) $payment['status'],
        ];
    }
}
