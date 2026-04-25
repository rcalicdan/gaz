<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum Typ: string implements EnumInterface
{
    case Date = 'date';

    case DateTime = 'datetime';

    case Dec = 'dec';

    case Int = 'int';

    case Time = 'time';

    case Txt = 'txt';
}
