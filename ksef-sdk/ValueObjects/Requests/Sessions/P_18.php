<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum P_18: string implements EnumInterface
{
    case OdwrotneObciazenie = '1';

    case Default = '2';
}
