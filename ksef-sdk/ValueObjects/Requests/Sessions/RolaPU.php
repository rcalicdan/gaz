<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum RolaPU: string implements EnumInterface
{
    case OrganEgzekucyjny = '1';

    case KomornikSadowy = '2';

    case PrzedstawicielPodatkowy = '3';
}
