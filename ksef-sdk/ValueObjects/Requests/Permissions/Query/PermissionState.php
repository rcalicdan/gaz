<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum PermissionState: string implements EnumInterface
{
    case Active = 'Active';

    case Inactive = 'Inactive';
}
