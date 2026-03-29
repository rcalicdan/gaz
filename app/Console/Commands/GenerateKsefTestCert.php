<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateKsefTestCert extends Command
{
    protected $signature = 'ksef:generate-cert {--nip= : The NIP to generate the cert for}';
    protected $description = 'Generates a self-signed PKCS12 certificate for KSeF Test Environment';

    public function handle()
    {
        $nip = $this->option('nip') ?: config('company.nip', '1111111111');
        $nip = preg_replace('/[^0-9]/', '', $nip);
        $password = config('ksef.certificate.password', 'test-password');
        
        $path = storage_path('app/ksef');
        File::ensureDirectoryExists($path);

        $this->info("Generating certificate for NIP: {$nip}");

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $distinguishedName =[
            'countryName' => 'PL',
            'organizationName' => 'Test Company',
            'commonName' => 'Test User',
            'serialNumber' => "TINPL-{$nip}",  
            '2.5.4.97' => "VATPL-{$nip}", // OrganizationIdentifier OID
        ];

        $csr  = openssl_csr_new($distinguishedName, $privateKey, ['digest_alg' => 'sha256']);
        $cert = openssl_csr_sign($csr, null, $privateKey, 730,['digest_alg' => 'sha256']);

        $p12Path = $path . '/cert.p12';
        
        openssl_pkcs12_export_to_file($cert, $p12Path, $privateKey, $password);

        $this->info("Certificate successfully generated at: {$p12Path}");
        $this->info("Ensure your .env matches this password: {$password}");
    }
}