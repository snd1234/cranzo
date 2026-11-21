<?php

return [

    /*
    | Razorpay keys stay in the environment. The key id is public and is
    | sent to Checkout. The key secret and webhook secret never leave the server.
    */
    'key_id' => env('RAZORPAY_KEY_ID'),
    'key_secret' => env('RAZORPAY_KEY_SECRET'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    'currency' => 'INR',

];
