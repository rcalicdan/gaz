<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\Persons;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum QueryType: string implements EnumInterface
{
    case PermissionsInCurrentContext = 'PermissionsInCurrentContext';

    case PermissionsGrantedInCurrentContext = 'PermissionsGrantedInCurrentContext';
}
