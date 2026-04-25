<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum P_9: string implements EnumInterface
{
    case Tax7 = '7';

    case Tax6_5 = '6.5';
}
