<?php

namespace App\Jobs;

use App\Services\OrderNotice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendOrderMail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    public function __construct(public int $orderId, public string $kind) {}

    public function handle(OrderNotice $notice): void
    {
        $notice->deliver($this->orderId, $this->kind);
    }

    public function failed(?Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::warning('order.email.gave_up', [
            'order_id' => $this->orderId,
            'kind' => $this->kind,
            'error' => $exception ? $exception::class : null,
        ]);
    }
}
