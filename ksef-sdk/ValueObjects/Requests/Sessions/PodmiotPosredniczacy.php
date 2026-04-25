<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum PodmiotPosredniczacy: string implements EnumInterface
{
    case Defaul = '1';
}
