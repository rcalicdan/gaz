<?php

namespace App\Listeners;

use App\Events\InvoiceAcceptedByKsef;
use App\Services\KpoEmailService;
use Illuminate\Support\Facades\Log;

class SendKpoDocumentEmail
{
    public function __construct(protected KpoEmailService $kpoEmailService) {}

    public function handle(InvoiceAcceptedByKsef $event): void
    {
        $invoice = $event->invoice;
        $pickup = $invoice->pickup;

        if (!$pickup) {
            Log::warning("SendKpoDocumentEmail: No pickup found for Invoice #{$invoice->id}, skipping.");
            return;
        }

        $kpoDocument = $pickup->kpoDocument;

        if (!$kpoDocument) {
            Log::warning("SendKpoDocumentEmail: No KPO document found for Pickup #{$pickup->id}, skipping.");
            return;
        }

        $this->kpoEmailService->sendToClient($kpoDocument);
    }
}