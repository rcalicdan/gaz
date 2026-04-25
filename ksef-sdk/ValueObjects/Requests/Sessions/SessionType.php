<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum SessionType: string implements EnumInterface
{
    case Online = 'Online';

    case Batch = 'Batch';
}
