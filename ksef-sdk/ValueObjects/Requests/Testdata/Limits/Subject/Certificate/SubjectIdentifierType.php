<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Limits\Subject\Certificate;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum SubjectIdentifierType: string implements EnumInterface
{
    use HasEquals;

    case Nip = 'Nip';

    case Pesel = 'Pesel';

    case Fingerprint = 'Fingerprint';
}
