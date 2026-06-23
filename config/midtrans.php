<?php

$clientKey = trim((string) env('MIDTRANS_CLIENT_KEY', ''));
$serverKey = trim((string) env('MIDTRANS_SERVER_KEY', ''));
$environment = strtolower(trim((string) env('MIDTRANS_ENV', 'sandbox')));
$looksLikeProductionKey = str_starts_with($clientKey, 'Mid-client-')
    || str_starts_with($serverKey, 'Mid-server-');

$isProduction = filter_var(
    env('MIDTRANS_IS_PRODUCTION', $environment === 'production' || $looksLikeProductionKey),
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
];
