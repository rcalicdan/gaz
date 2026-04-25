<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum ZnacznikZaplatyCzesciowej: string implements EnumInterface
{
    case Default = '1';

    case WCalosci = '2';
}
