<?php

namespace App\Providers;

use App\Events\InvoiceAcceptedByKsef;
use App\Listeners\SendInvoiceConfirmationEmail;
use App\Listeners\SendKpoDocumentEmail; 
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(InvoiceAcceptedByKsef::class, SendInvoiceConfirmationEmail::class);
        Event::listen(InvoiceAcceptedByKsef::class, SendKpoDocumentEmail::class);
    }
}