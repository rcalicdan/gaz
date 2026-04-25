<?php

use App\Services\KsefService;

it('throws exception if encryption key is invalid', function () {
    config(['ksef.encryption_key' => base64_encode('too_short_key')]);

    $service = new KsefService();
    
    $service->getClient('1111111111');
    
})->throws(Exception::class, 'Invalid KSEF_ENCRYPTION_KEY');


it('throws exception if certificate is missing', function () {
    $validKey = base64_encode(random_bytes(32));
    
    config([
        'ksef.encryption_key' => $validKey,
        'ksef.certificate.path' => 'storage/app/ksef/non_existent.p12'
    ]);

    $service = new KsefService();
    
    $service->getClient('1111111111');
    
})->throws(Exception::class, 'KSeF Certificate not found');