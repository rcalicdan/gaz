<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\EuEntities;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum EuEntityPermissionType: string implements EnumInterface
{
    case VatUeManage = 'VatUeManage';

    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';

    case Introspection = 'Introspection';
}
