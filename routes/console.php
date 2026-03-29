<?php

use App\Enums\KsefStatus;
use App\Jobs\PollKsefInvoiceStatusJob;
use App\Models\Invoice;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $pendingInvoices = Invoice::where('ksef_status', KsefStatus::SENT_TO_KSEF)->get();

    foreach ($pendingInvoices as $invoice) {
        PollKsefInvoiceStatusJob::dispatch($invoice);
    }
})->everyFiveMinutes();