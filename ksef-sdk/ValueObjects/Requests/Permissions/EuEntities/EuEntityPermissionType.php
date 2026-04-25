<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\EuEntities;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum EuEntityPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';
}
