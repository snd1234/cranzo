<?php

return [
    'business_state_id' => env('BUSINESS_STATE_ID'),
    'low_stock' => (int) env('LOW_STOCK_THRESHOLD', 5),
    'abandoned_hours' => (int) env('ABANDONED_CART_HOURS', 2),
];
