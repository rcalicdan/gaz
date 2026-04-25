<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\KpoDocument;
use App\Enums\KsefStatus;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientAppDocumentService
{
    public function getInvoices(int $clientId, array $filters)
    {
        $query = Invoice::where('client_id', $clientId)
            ->with('pickup.wasteType')
            ->orderBy('issue_date', 'desc');

        if (!empty($filters['date_from'])) {
            $query->whereDate('issue_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('issue_date', '<=', $filters['date_to']);
        }

        $invoices = $query->get()->map(function ($invoice) {
            return $this->appendDynamicInvoiceStatus($invoice);
        });

        if (!empty($filters['status'])) {
            $statusFilter = strtolower($filters['status']);
            $invoices = $invoices->filter(function ($invoice) use ($statusFilter) {
                return strtolower($invoice->dynamic_status) === $statusFilter;
            })->values();
        }

        return $invoices;
    }

    public function getInvoice(int $clientId, int $invoiceId)
    {
        $invoice = Invoice::with(['pickup.wasteType', 'client'])
            ->where('client_id', $clientId)
            ->where('id', $invoiceId)
            ->first();

        if (!$invoice) {
            throw ValidationException::withMessages(['invoice' => ['Invoice not found or access denied.']]);
        }

        return $this->appendDynamicInvoiceStatus($invoice);
    }

    public function getKpoDocuments(int $clientId, array $filters)
    {
        $query = KpoDocument::where('client_id', $clientId)
            ->with('pickup.wasteType')
            ->orderBy('created_at', 'desc');

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('kpo_number', 'like', $search)
                    ->orWhere('waste_code', 'like', $search);
            });
        }

        return $query->get();
    }

    public function getKpoDocument(int $clientId, int $kpoId): KpoDocument
    {
        $kpo = KpoDocument::with(['pickup.wasteType', 'client'])
            ->where('client_id', $clientId)
            ->where('id', $kpoId)
            ->first();

        if (!$kpo) {
            throw ValidationException::withMessages(['kpo' => ['KPO document not found or access denied.']]);
        }

        return $kpo;
    }

    public function downloadKpoDocument(int $clientId, int $kpoId): StreamedResponse
    {
        $kpo = $this->getKpoDocument($clientId, $kpoId);

        if (!$kpo->hasPdf()) {
            throw ValidationException::withMessages(['kpo' => ['PDF file is not available for this document.']]);
        }

        $filename = "KPO_{$kpo->kpo_number}.pdf";

        return Storage::download($kpo->pdf_path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadInvoice(int $clientId, int $invoiceId)
    {
        $invoice = $this->getInvoice($clientId, $invoiceId);

        if (!$invoice->pdf_url && !$invoice->ksef_xml_content) {
            throw ValidationException::withMessages([
                'invoice' => ['Invoice document is not available for download yet.']
            ]);
        }

        $filename = "FAKTURA_" . str_replace('/', '_', $invoice->invoice_number) . ".pdf";

        if (Storage::exists($invoice->pdf_url)) {
            return Storage::download($invoice->pdf_url, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        return redirect($invoice->pdf_url);
    }

    protected function appendDynamicInvoiceStatus(Invoice $invoice): Invoice
    {
        $now = Carbon::now();
        $isPaid = $invoice->ksef_status === KsefStatus::PAID;
        $isOverdue = !$isPaid && $invoice->due_date && $invoice->due_date->isPast();

        if ($isPaid) {
            $invoice->dynamic_status = 'Paid';
        } elseif ($isOverdue) {
            $invoice->dynamic_status = 'Overdue';
        } else {
            $invoice->dynamic_status = 'Unpaid';
        }

        return $invoice;
    }
}
