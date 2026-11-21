<?php

namespace App\Services\Payments;

interface RazorpayClient
{
    public function createOrder(int $amountPaise, string $receipt, array $notes): array;

    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): void;

    public function verifyWebhookSignature(string $body, string $signature): void;

    public function fetchPayment(string $paymentId): array;

    public function capture(string $paymentId, int $amountPaise): array;

    public function refund(string $paymentId, int $amountPaise): array;
}
