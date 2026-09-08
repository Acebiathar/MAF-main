<?php

return [
    'amount' => 50000,
    'currency' => 'UGX',
    'months' => (int) env('PHARMACY_SUBSCRIPTION_MONTHS', 1),
    'payment_number' => env('PHARMACY_SUBSCRIPTION_PHONE', '0773496048'),
];
