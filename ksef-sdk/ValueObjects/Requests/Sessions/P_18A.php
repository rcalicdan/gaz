<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum P_18A: string implements EnumInterface
{
    case MechanizmPodzielonejPlatnosci = '1';

    case Default = '2';
}
