<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum P_23: string implements EnumInterface
{
    case ProceduraUproszczona = '1';

    case Default = '2';
}
