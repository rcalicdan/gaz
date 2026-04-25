<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum CertificateStatus: string implements EnumInterface
{
    use HasEquals;

    case Active = 'Active';

    case Blocked = 'Blocked';

    case Revoked = 'Revoked';

    case Expired = 'Expired';
}
