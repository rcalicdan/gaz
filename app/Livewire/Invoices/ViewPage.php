<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use App\Enums\KsefStatus;
use App\Jobs\SendInvoiceToKsefJob;
use App\Jobs\PollKsefInvoiceStatusJob;
use App\Mail\KsefInvoiceConfirmation;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ViewPage extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load(['client', 'pickup.wasteType', 'logs']);
    }

    public function retrySend()
    {
        if ($this->invoice->ksef_status->canResend()) {
            $this->invoice->logs()->create([
                'level' => 'info',
                'message' => 'Użytkownik zlecił ponowną wysyłkę do KSeF z panelu.'
            ]);

            SendInvoiceToKsefJob::dispatch($this->invoice);
            $this->dispatch('show-message', ['type' => 'success', 'message' => __('Invoice queued for sending to KSeF.')]);
            $this->invoice->ksef_status = KsefStatus::SENT_TO_KSEF;

            $this->invoice->load('logs');
        }
    }

    public function syncStatus()
    {
        if ($this->invoice->ksef_status === KsefStatus::SENT_TO_KSEF) {
            $this->invoice->logs()->create([
                'level' => 'info',
                'message' => 'Użytkownik zażądał ręcznego sprawdzenia statusu.'
            ]);

            PollKsefInvoiceStatusJob::dispatch($this->invoice);
            $this->dispatch('show-message', ['type' => 'info', 'message' => __('Status update requested from KSeF.')]);

            sleep(1);
            $this->invoice->refresh();
        }
    }

    public function resendEmail()
    {
        if ($this->invoice->ksef_status === KsefStatus::ACCEPTED) {

            $this->invoice->logs()->create([
                'level' => 'info',
                'message' => 'Użytkownik zlecił ręczną wysyłkę e-maila z panelu.'
            ]);

            $emailService = app(\App\Services\InvoiceEmailService::class);
            $success = $emailService->sendConfirmation($this->invoice);

            if ($success) {
                $this->dispatch('show-message', ['type' => 'success', 'message' => __('Email sent successfully.')]);
            } else {
                $this->dispatch('show-message', ['type' => 'error', 'message' => __('Failed to send email. Check the Activity Log.')]);
            }

            $this->invoice->refresh();
            $this->invoice->load('logs');
        }
    }

    public function render()
    {
        return view('livewire.invoices.view-page');
    }
}
