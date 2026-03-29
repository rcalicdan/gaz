<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\KsefService;
use App\Enums\KsefStatus;
use App\Events\InvoiceAcceptedByKsef;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PollKsefInvoiceStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function handle(KsefService $ksef): void
    {
        $client = $ksef->getClient();
        
        try {
            $response = $client->sessions()->invoices()->status([
                'referenceNumber' => $this->invoice->ksef_session_reference,
                'invoiceReferenceNumber' => $this->invoice->ksef_reference_number
            ])->object();

            if ($response->status->code === 200 && isset($response->ksefNumber)) {
                $this->invoice->update([
                    'ksef_status' => KsefStatus::ACCEPTED,
                    'ksef_reference_number' => $response->ksefNumber,
                ]);

                $this->invoice->logs()->create([
                    'level' => 'success', 
                    'message' => "KSeF zaakceptował fakturę. Nadano oficjalny numer: {$response->ksefNumber}"
                ]);
                
                Log::info("Invoice #{$this->invoice->id} ACCEPTED by KSeF.", [
                    'ksef_number' => $response->ksefNumber
                ]);

                event(new InvoiceAcceptedByKsef($this->invoice));
            } 
            elseif ($response->status->code >= 400) {
                $this->invoice->update(['ksef_status' => KsefStatus::REJECTED]);
                
                $this->invoice->logs()->create([
                    'level' => 'error', 
                    'message' => 'Odrzucono przez KSeF: ' . $response->status->description,
                    'context' => $response->status
                ]);

                Log::warning("Invoice #{$this->invoice->id} REJECTED by KSeF.",[
                    'ksef_response' => (array) $response->status
                ]);
            }
        } catch (\Exception $e) {
            $this->invoice->logs()->create([
                'level' => 'warning', 
                'message' => 'Błąd podczas odpytywania o status: ' . $e->getMessage()
            ]);

            Log::error("Error polling KSeF status for Invoice #{$this->invoice->id}.",[
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}