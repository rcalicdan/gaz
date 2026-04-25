<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum CertificateType: string implements EnumInterface
{
    use HasEquals;

    case Authentication = 'Authentication';

    case Offline = 'Offline';
}
