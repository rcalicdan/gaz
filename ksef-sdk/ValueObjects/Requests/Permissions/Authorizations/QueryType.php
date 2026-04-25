<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Authorizations;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum QueryType: string implements EnumInterface
{
    case Granted = 'Granted';

    case Received = 'Received';
}
