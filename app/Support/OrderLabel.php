<?php

namespace App\Support;

class OrderLabel
{
    public static function summary(object $order): string
    {
        if (! empty($order->cancelled_at)) {
            return 'Cancelled';
        }

        return match ((int) ($order->delivery_status ?? 0)) {
            2 => 'Shipped',
            3 => 'Delivered',
            default => match ((int) ($order->order_status ?? 0)) {
                1 => 'Received',
                2 => 'Confirmed',
                3 => 'Closed',
                default => 'In progress',
            },
        };
    }

    public static function tone(object $order): string
    {
        if (! empty($order->cancelled_at)) {
            return 'bg-red-50 text-red-800';
        }

        return match ((int) ($order->delivery_status ?? 0)) {
            2 => 'bg-amber-50 text-amber-800',
            3 => 'bg-green-50 text-green-800',
            default => 'bg-blue-50 text-blue-800',
        };
    }
}
