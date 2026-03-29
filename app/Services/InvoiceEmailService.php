<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Enums\EmailStatus;
use App\Mail\KsefInvoiceConfirmation;
use App\Models\EmailLog;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceEmailService
{
    public function sendConfirmation(Invoice $invoice): bool
    {
        if (!$invoice->client || !$invoice->client->email) {
            $invoice->logs()->create([
                'level' => 'warning',
                'message' => 'Nie można wysłać e-maila: Brak adresu e-mail klienta.'
            ]);
            return false;
        }

        $recipientEmail = $invoice->client->email;

        $emailLog = EmailLog::create([
            'document_type' => DocumentType::INVOICE,
            'document_id' => $invoice->id,
            'recipient_email' => $recipientEmail,
            'status' => EmailStatus::SENT, 
            'sent_by_user_id' => auth()->id(), 
            'sent_at' => now(),
        ]);

        try {
            Mail::to($recipientEmail)->send(new KsefInvoiceConfirmation($invoice));

            $invoice->update(['is_emailed' => true]);

            $invoice->logs()->create([
                'level' => 'success',
                'message' => "E-mail z potwierdzeniem KSeF wysłany do: {$recipientEmail}"
            ]);

            return true;

        } catch (\Exception $e) {
            $emailLog->update([
                'status' => EmailStatus::FAILED,
                'error_message' => $e->getMessage(),
            ]);

            $invoice->update(['is_emailed' => false]);

            $invoice->logs()->create([
                'level' => 'error',
                'message' => "Błąd wysyłki e-maila (SMTP): " . $e->getMessage()
            ]);

            Log::error("Failed to send Invoice email for Invoice #{$invoice->id}", [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}