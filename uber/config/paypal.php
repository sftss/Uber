<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

 return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'payment_action'    => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'payment_action'    => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    ],
    'currency'  => 'EUR',
    'locale'    => 'fr_FR',
    'validate_ssl' => true,
];

