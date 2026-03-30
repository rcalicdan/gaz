<?php

return [
    'mode' => env('KSEF_MODE', 'test'),
    'self_billing' => env('KSEF_SELF_BILLING', true),
    'issuer_nip' => env('KSEF_ISSUER_NIP', '1234567890'),
    'certificate' => [
        'path' => env('KSEF_CERTIFICATE_PATH', storage_path('app/ksef/cert.p12')),
        'password' => env('KSEF_CERTIFICATE_PASSWORD', 'test-password'),
    ],
];