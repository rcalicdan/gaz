<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum RodzajFaktury: string implements EnumInterface
{
    case VatRr = 'VAT_RR';

    case KorVatRr = 'KOR_VAT_RR';
}
