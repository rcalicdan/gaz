<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum RachunekWlasnyBanku: string implements EnumInterface
{
    case WierzytelnosciPieniezne = '1';

    case PobranieNaleznosci = '2';

    case GospodarkaWlasna = '3';
}
