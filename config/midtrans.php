<?php

$clientKey = trim((string) env('MIDTRANS_CLIENT_KEY', ''));
$serverKey = trim((string) env('MIDTRANS_SERVER_KEY', ''));
$environment = strtolower(trim((string) env('MIDTRANS_ENV', 'sandbox')));

// Hapus deteksi otomatis berdasarkan awalan Mid- karena key Sandbox baru juga bisa berawalan Mid-
$isProduction = filter_var(
    env('MIDTRANS_IS_PRODUCTION', $environment === 'production'),
    FILTER_VALIDATE_BOOLEAN
);

return [
    'merchant_id' => trim((string) env('MIDTRANS_MERCHANT_ID', '')),
    'client_key' => $clientKey,
    'server_key' => $serverKey,
    'env' => $isProduction ? 'production' : 'sandbox',
    'is_production' => $isProduction,
    'snap_url' => $isProduction
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
    'order_prefix' => env('MIDTRANS_ORDER_PREFIX', env('APP_ENV') === 'production' ? '' : 'dev-'),
];