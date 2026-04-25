<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Persons;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum PersonPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';

    case CredentialsManage = 'CredentialsManage';

    case CredentialsRead = 'CredentialsRead';

    case Introspection = 'Introspection';

    case SubunitManage = 'SubunitManage';

    case EnforcementOperations = 'EnforcementOperations';
}
