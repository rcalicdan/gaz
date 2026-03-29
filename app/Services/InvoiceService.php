<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Pickup;
use App\Enums\KsefStatus;
use App\Jobs\SendInvoiceToKsefJob;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function generateForPickup(Pickup $pickup): Invoice
    {
        return DB::transaction(function () use ($pickup) {
            $netAmount = $pickup->waste_quantity * $pickup->applied_price_rate;
            $taxRate = $pickup->client->tax_rate ?? 23;
            $vatAmount = round($netAmount * ($taxRate / 100), 2);
            $grossAmount = $netAmount + $vatAmount;

            $invoice = Invoice::create([
                'pickup_id' => $pickup->id,
                'client_id' => $pickup->client_id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'issue_date' => now(),
                'due_date' => now()->addDays(14),
                'net_amount' => $netAmount,
                'vat_amount' => $vatAmount,
                'gross_amount' => $grossAmount,
                'ksef_status' => KsefStatus::PENDING,
            ]);

            SendInvoiceToKsefJob::dispatch($invoice);

            return $invoice;
        });
    }

    private function generateInvoiceNumber(): string
    {
        $count = Invoice::whereYear('created_at', now()->year)->count() + 1;
        return \sprintf('FV/%s/%04d', now()->format('Y/m'), $count);
    }
}