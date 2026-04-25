<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum FormType: string implements EnumInterface
{
    case FA = 'FA';

    case PEF = 'PEF';

    case RR = 'RR';
}
