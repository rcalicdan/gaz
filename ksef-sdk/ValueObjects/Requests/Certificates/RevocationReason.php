<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum RevocationReason: string implements EnumInterface
{
    use HasEquals;

    case Unspecified = 'Unspecified';

    case Superseded = 'Superseded';

    case KeyCompromise = 'KeyCompromise';
}
