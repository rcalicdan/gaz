<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Subject;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum SubjectType: string implements EnumInterface
{
    case EnforcementAuthority = 'EnforcementAuthority';

    case VatGroup = 'VatGroup';

    case JST = 'JST';
}
