<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\KsefService;
use App\Services\KsefInvoiceMapper;
use App\Enums\KsefStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInvoiceToKsefJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    public function __construct(public Invoice $invoice) {}

    public function handle(KsefService $ksef, KsefInvoiceMapper $mapper): void
    {
        $this->invoice->logs()->create(['level' => 'info', 'message' => 'Rozpoczęto proces wysyłki do KSeF...']);
        Log::info("Starting KSeF submission for Invoice #{$this->invoice->id}");

        $fakturaDto = $mapper->mapToFaktura($this->invoice);
        
        try {
            $result = $ksef->sendToKsef($this->invoice, $fakturaDto);

            $this->invoice->update([
                'ksef_status' => KsefStatus::SENT_TO_KSEF,
                'ksef_session_reference' => $result['session_id'],
                'ksef_reference_number' => $result['invoice_ref'],
            ]);

            $this->invoice->logs()->create([
                'level' => 'success', 
                'message' => "Faktura wysłana. Oczekuje na przetworzenie (Ref: {$result['invoice_ref']})"
            ]);

            Log::info("Invoice #{$this->invoice->id} successfully sent to KSeF.", [
                'session_id' => $result['session_id'],
                'invoice_ref' => $result['invoice_ref']
            ]);
            
        } catch (\Exception $e) {
            $this->invoice->update(['ksef_status' => KsefStatus::REJECTED]);
            
            $this->invoice->logs()->create([
                'level' => 'error', 
                'message' => 'Błąd wysyłki do KSeF: ' . $e->getMessage()
            ]);
            
            Log::error("Failed to send Invoice #{$this->invoice->id} to KSeF.",[
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}