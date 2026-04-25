<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum BrakID: string implements EnumInterface
{
    case Default = '1';
}
