<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum StatusInfoPodatnika: string implements EnumInterface
{
    case Likwidacja = '1';

    case Restrukturyzacja = '2';

    case Upadlosc = '3';

    case Spadek = '4';
}
