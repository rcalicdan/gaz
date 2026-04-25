<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum DateType: string implements EnumInterface
{
    case Issue = 'Issue';

    case Invoicing = 'Invoicing';

    case PermanentStorage = 'PermanentStorage';
}
