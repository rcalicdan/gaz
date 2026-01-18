<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Enums\EmailStatus;
use App\Mail\KpoDocumentMail;
use App\Models\EmailLog;
use App\Models\KpoDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class KpoEmailService
{
    public function sendToClient(KpoDocument $kpoDocument, ?string $customMessage = null): bool
    {
        if (!$kpoDocument->client || !$kpoDocument->client->email) {
            Log::error('Cannot send KPO email: Client or email not found', [
                'kpo_id' => $kpoDocument->id
            ]);
            return false;
        }

        return $this->sendEmail(
            $kpoDocument,
            $kpoDocument->client->email,
            $customMessage,
            'client_registered_email'
        );
    }

    public function sendToCustomEmail(
        KpoDocument $kpoDocument,
        string $recipientEmail,
        ?string $customMessage = null,
        ?string $authorizationReason = null
    ): bool {
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            Log::error('Invalid email address provided', [
                'kpo_id' => $kpoDocument->id,
                'email' => $recipientEmail
            ]);
            return false;
        }

        Log::info('KPO document sent to custom email address', [
            'kpo_id' => $kpoDocument->id,
            'recipient' => $recipientEmail,
            'authorized_by' => auth()->id(),
            'authorization_reason' => $authorizationReason,
            'timestamp' => now()->toIso8601String()
        ]);

        return $this->sendEmail(
            $kpoDocument,
            $recipientEmail,
            $customMessage,
            'custom_email_authorized'
        );
    }

    protected function sendEmail(
        KpoDocument $kpoDocument,
        string $recipientEmail,
        ?string $customMessage,
        string $emailType
    ): bool {
        $kpoDocument->ensurePdfIsReady();

        $emailLog = EmailLog::create([
            'document_type' => DocumentType::KPO,
            'document_id' => $kpoDocument->id,
            'recipient_email' => $recipientEmail,
            'status' => EmailStatus::SENT,
            'sent_by_user_id' => auth()->id(),
            'sent_at' => now(),
        ]);

        try {
            Mail::to($recipientEmail)->send(
                new KpoDocumentMail($kpoDocument, $recipientEmail, $customMessage)
            );

            $kpoDocument->update(['is_emailed' => true]);

            Log::info('KPO email sent successfully', [
                'kpo_id' => $kpoDocument->id,
                'recipient' => $recipientEmail,
                'email_type' => $emailType,
                'email_log_id' => $emailLog->id
            ]);

            return true;

        } catch (\Exception $e) {
            $emailLog->update([
                'status' => EmailStatus::FAILED,
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Failed to send KPO email', [
                'kpo_id' => $kpoDocument->id,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    public function retryEmail(EmailLog $emailLog, ?string $customMessage = null): bool
    {
        if (!$emailLog->status->needsRetry()) {
            Log::warning('Cannot retry email that is not failed or bounced', [
                'email_log_id' => $emailLog->id,
                'status' => $emailLog->status->value
            ]);
            return false;
        }

        $kpoDocument = KpoDocument::find($emailLog->document_id);
        
        if (!$kpoDocument) {
            Log::error('KPO document not found for email retry', [
                'email_log_id' => $emailLog->id,
                'document_id' => $emailLog->document_id
            ]);
            return false;
        }

        return $this->sendEmail(
            $kpoDocument,
            $emailLog->recipient_email,
            $customMessage,
            'retry_attempt'
        );
    }

    /**
     * Get email sending history for a KPO document (GDPR audit trail)
     */
    public function getEmailHistory(KpoDocument $kpoDocument): \Illuminate\Database\Eloquent\Collection
    {
        return $kpoDocument->emailLogs()
            ->with('sentBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get email statistics for a KPO document
     */
    public function getEmailStatistics(KpoDocument $kpoDocument): array
    {
        $logs = $kpoDocument->emailLogs;

        return [
            'total_sent' => $logs->count(),
            'successful' => $logs->where('status', EmailStatus::SENT)->count(),
            'failed' => $logs->where('status', EmailStatus::FAILED)->count(),
            'bounced' => $logs->where('status', EmailStatus::BOUNCED)->count(),
            'last_sent_at' => $logs->where('status', EmailStatus::SENT)->first()?->sent_at,
            'unique_recipients' => $logs->pluck('recipient_email')->unique()->count(),
        ];
    }
}