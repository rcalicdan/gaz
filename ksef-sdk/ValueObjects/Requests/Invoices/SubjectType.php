<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum SubjectType: string implements EnumInterface
{
    case Subject1 = 'Subject1';

    case Subject2 = 'Subject2';

    case Subject3 = 'Subject3';

    case subjectAuthorized = 'SubjectAuthorized';
}
