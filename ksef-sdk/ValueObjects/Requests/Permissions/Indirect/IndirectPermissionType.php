<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Indirect;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum IndirectPermissionType: string implements EnumInterface
{
    case InvoiceWrite = 'InvoiceWrite';

    case InvoiceRead = 'InvoiceRead';
}
