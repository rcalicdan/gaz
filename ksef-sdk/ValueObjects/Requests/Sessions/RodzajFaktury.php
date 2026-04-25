<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum RodzajFaktury: string implements EnumInterface
{
    case Vat = 'VAT';

    case Kor = 'KOR';

    case Zal = 'ZAL';

    case Roz = 'ROZ';

    case Upr = 'UPR';

    case KorZal = 'KOR_ZAL';

    case KorRoz = 'KOR_ROZ';

    case VatRr = 'VAT_RR';

    case KorVatRr = 'KOR_VAT_RR';
}
