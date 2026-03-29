<?php

namespace App\Listeners;

use App\Events\InvoiceAcceptedByKsef;
use App\Services\InvoiceEmailService;

class SendInvoiceConfirmationEmail
{
    public function __construct(protected InvoiceEmailService $emailService) {}

    public function handle(InvoiceAcceptedByKsef $event): void
    {
        if (config('ksef.mode') === 'test') {
            sleep(1);
        }

        $this->emailService->sendConfirmation($event->invoice);
    }
}
