<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum JST: string implements EnumInterface
{
    case Yes = '1';

    case No = '2';
}
