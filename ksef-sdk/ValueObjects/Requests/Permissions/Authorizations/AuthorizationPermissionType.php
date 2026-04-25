<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Authorizations;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum AuthorizationPermissionType: string implements EnumInterface
{
    case SelfInvoicing = 'SelfInvoicing';

    case RRInvoicing = 'RRInvoicing';

    case TaxRepresentative = 'TaxRepresentative';

    case PefInvoicing = 'PefInvoicing';
}
