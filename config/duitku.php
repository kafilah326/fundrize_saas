<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE', ''),
    'api_key' => env('DUITKU_API_KEY', ''),
    'sandbox' => env('DUITKU_SANDBOX', true),
    
    'callback_url' => env('APP_URL') . '/webhooks/duitku/callback',
    'return_url' => env('APP_URL') . '/payment/duitku/return',
];
