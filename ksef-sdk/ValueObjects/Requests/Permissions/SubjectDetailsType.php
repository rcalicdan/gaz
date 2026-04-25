<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum SubjectDetailsType: string implements EnumInterface
{
    case PersonByIdentifier = 'PersonByIdentifier';

    case PersonByFingerprintWithIdentifier = 'PersonByFingerprintWithIdentifier';

    case PersonByFingerprintWithoutIdentifier = 'PersonByFingerprintWithoutIdentifier';

    case EntityByFingerprint = 'EntityByFingerprint';
}
