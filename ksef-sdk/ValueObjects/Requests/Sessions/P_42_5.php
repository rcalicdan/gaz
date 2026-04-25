<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum P_42_5: string implements EnumInterface
{
    case DokumentWywozu = '1';

    case Default = '2';
}
