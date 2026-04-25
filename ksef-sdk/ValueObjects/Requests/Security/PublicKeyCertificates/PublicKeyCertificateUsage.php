<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum PublicKeyCertificateUsage: string implements EnumInterface
{
    use HasEquals;

    case KsefTokenEncryption = 'KsefTokenEncryption';

    case SymmetricKeyEncryption = 'SymmetricKeyEncryption';
}
