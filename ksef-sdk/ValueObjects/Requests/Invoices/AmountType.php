<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum AmountType: string implements EnumInterface
{
    case Brutto = 'Brutto';

    case Netto = 'Netto';

    case Vat = 'Vat';
}
