<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum TypKorekty: string implements EnumInterface
{
    case Pierwotna = '1';

    case Korygujaca = '2';

    case Inna = '3';
}
