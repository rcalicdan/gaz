<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum BuyerIdentifierType: string implements EnumInterface
{
    case None = 'None';

    case Other = 'Other';

    case Nip = 'Nip';

    case VatUe = 'VatUe';
}
