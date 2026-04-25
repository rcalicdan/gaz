<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum FormaPlatnosci: string implements EnumInterface
{
    case Przelew = '1';
}
