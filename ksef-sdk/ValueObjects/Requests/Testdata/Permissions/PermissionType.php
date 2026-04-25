<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum PermissionType: string implements EnumInterface
{
    case InvoiceRead = 'InvoiceRead';

    case InvoiceWrite = 'InvoiceWrite';

    case Introspection = 'Introspection';

    case CredentialsRead = 'CredentialsRead';

    case CredentialsManage = 'CredentialsManage';

    case EnforcementOperations = 'EnforcementOperations';

    case SubunitManage = 'SubunitManage';
}
