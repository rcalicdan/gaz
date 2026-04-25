<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum SortOrder: string implements EnumInterface
{
    use HasEquals;

    case Asc = 'Asc';

    case Desc = 'Desc';
}
