<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Support;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum KeyType implements EnumInterface
{
    case Camel;

    case Snake;
}
