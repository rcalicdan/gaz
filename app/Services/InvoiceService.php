<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\KpoDocument;
use App\Models\Pickup;
use App\Enums\KsefStatus;
use App\Jobs\SendInvoiceToKsefJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public function __construct(protected KpoPdfService $kpoPdfService) {}

    public function generateForPickup(Pickup $pickup, bool $forceKsefDispatch = false): Invoice
    {
        return DB::transaction(function () use ($pickup, $forceKsefDispatch) {
            $netAmount = $pickup->waste_quantity * $pickup->applied_price_rate;
            $taxRate = $pickup->client->tax_rate ?? 23;
            $vatAmount = round($netAmount * ($taxRate / 100), 2);
            $grossAmount = $netAmount + $vatAmount;

            $invoice = Invoice::create([
                'pickup_id'      => $pickup->id,
                'client_id'      => $pickup->client_id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'issue_date'     => now(),
                'due_date'       => now()->addDays(14),
                'net_amount'     => $netAmount,
                'vat_amount'     => $vatAmount,
                'gross_amount'   => $grossAmount,
                'ksef_status'    => KsefStatus::PENDING,
            ]);

            $this->generateKpoForPickup($pickup, $invoice);

            if ($forceKsefDispatch || config('ksef.auto_send_on_pickup')) {
                SendInvoiceToKsefJob::dispatch($invoice);
            } else {
                $invoice->logs()->create([
                    'level'   => 'info',
                    'message' => 'Faktura utworzona. Automatyczna wysyłka do KSeF jest wyłączona.',
                ]);
            }

            return $invoice;
        });
    }

    private function generateKpoForPickup(Pickup $pickup, Invoice $invoice): void
    {
        try {
            $kpoDocument = KpoDocument::firstOrCreate(
                ['pickup_id' => $pickup->id],
                [
                    'client_id'  => $pickup->client_id,
                    'waste_code' => $pickup->wasteType?->code,
                    'quantity'   => $pickup->waste_quantity,
                    'kpo_number' => $this->generateKpoNumber(),
                ]
            );

            $this->kpoPdfService->generateKpoDocument($kpoDocument);

            $invoice->logs()->create([
                'level'   => 'info',
                'message' => "Wygenerowano dokument KPO: {$kpoDocument->kpo_number}",
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to generate KPO for Pickup #{$pickup->id}: " . $e->getMessage());

            $invoice->logs()->create([
                'level'   => 'warning',
                'message' => 'Nie udało się wygenerować dokumentu KPO: ' . $e->getMessage(),
            ]);
        }
    }

    private function generateInvoiceNumber(): string
    {
        $count = Invoice::whereYear('created_at', now()->year)->count() + 1;
        return \sprintf('FV/%s/%04d', now()->format('Y/m'), $count);
    }

    private function generateKpoNumber(): string
    {
        $year = now()->year;

        $lastKpo = KpoDocument::whereYear('created_at', $year)
            ->whereNotNull('kpo_number')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastKpo
            ? ((int) end(explode('-', $lastKpo->kpo_number))) + 1
            : 1;

        return sprintf('KPO-%d-%05d', $year, $newNumber);
    }
}