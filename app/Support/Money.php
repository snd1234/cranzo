<?php

namespace App\Support;

class Money
{
    public static function discount(string $type, float $amount, float $subtotal, ?float $cap): float
    {
        $discount = $type === 'fixed' ? $amount : round($subtotal * $amount / 100, 2);
        if ($cap !== null) {
            $discount = min($discount, $cap);
        }

        return round(min($discount, $subtotal), 2);
    }

    public static function tax(float $taxable, ?float $rate, ?int $shipState, ?int $businessState): array
    {
        if ($rate === null || $rate <= 0 || $taxable <= 0) {
            return ['amount' => 0.0, 'label' => 'GST not configured'];
        }

        $amount = round($taxable * $rate / 100, 2);
        if ($businessState && $shipState && $shipState === $businessState) {
            return ['amount' => $amount, 'label' => 'CGST + SGST '.$rate.'%'];
        }
        if ($businessState && $shipState) {
            return ['amount' => $amount, 'label' => 'IGST '.$rate.'%'];
        }

        return ['amount' => $amount, 'label' => 'GST '.$rate.'%'];
    }
}
