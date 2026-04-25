<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum InvoicingMode: string implements EnumInterface
{
    case Online = 'Online';

    case Offline = 'Offline';
}
