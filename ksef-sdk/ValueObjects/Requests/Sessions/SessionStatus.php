<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum SessionStatus: string implements EnumInterface
{
    case InProgress = 'InProgress';

    case Succeeded = 'Succeeded';

    case Failed = 'Failed';

    case Cancelled = 'Cancelled';
}
