<?php

namespace App\Services;

use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;
use Rcalicdan\KSEFClient\ClientBuilder;
use Rcalicdan\KSEFClient\Contracts\Resources\ClientResourceInterface;
use Rcalicdan\KSEFClient\Factories\EncryptionKeyFactory;
use Rcalicdan\KSEFClient\ValueObjects\CertificatePath;
use Rcalicdan\KSEFClient\ValueObjects\Mode;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

class KsefService
{
    public function getClient(): ClientResourceInterface
    {
        $mode = Mode::from(config('ksef.mode', 'test'));
        $nip = preg_replace('/[^0-9]/', '', config('company.nip'));

        $builder = (new ClientBuilder())
            ->withMode($mode)
            ->withIdentifier(new NIP($nip))
            ->withEncryptionKey(EncryptionKeyFactory::makeRandom())
            ->withValidateXml(false) 
            ->withCertificatePath(
                CertificatePath::from(
                    storage_path('app/ksef/cert.p12'),
                    config('ksef.certificate.password', 'test-password')
                )
            );

        if ($mode === Mode::Test) {
            $builder->withVerifyCertificateChain(false);
        }

        return $builder->build();
    }

    public function sendToKsef(Invoice $invoice, $fakturaDto): array
    {
        try {
            $client = $this->getClient();

            $openResponse = $client->sessions()->online()->open(['formCode' => 'FA (3)'])->object();
            $sessionId = $openResponse->referenceNumber;

            $sendResponse = $client->sessions()->online()->send([
                'referenceNumber' => $sessionId,
                'faktura' => $fakturaDto
            ])->object();

            $invoiceRef = $sendResponse->referenceNumber;

            $client->sessions()->online()->close([
                'referenceNumber' => $sessionId
            ]);

            return[
                'session_id' => $sessionId,
                'invoice_ref' => $invoiceRef
            ];
        } catch (Exception $e) {
            Log::error("KSeF Send Error: " . $e->getMessage(), ['invoice_id' => $invoice->id]);
            throw $e;
        }
    }
}