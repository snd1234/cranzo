<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_percent_discount_respects_the_cap(): void
    {
        $this->assertSame(50.0, Money::discount('percent', 20, 1000, 50));
    }

    public function test_gst_stays_zero_when_no_rate_is_configured(): void
    {
        $tax = Money::tax(500, null, 9, 9);

        $this->assertSame(0.0, $tax['amount']);
    }

    public function test_same_state_is_labelled_split_gst(): void
    {
        $tax = Money::tax(1000, 18, 9, 9);

        $this->assertSame(180.0, $tax['amount']);
        $this->assertStringContainsString('CGST', $tax['label']);
    }
}
