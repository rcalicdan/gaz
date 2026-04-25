<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Actions\GenerateQRCodes;

interface InvoiceHashInterface
{
    public function getInvoiceHash(): string;
}
