<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\HttpClient;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Contracts\EqualsInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum Method: string implements EnumInterface, EqualsInterface
{
    use HasEquals;

    case Get = 'GET';

    case Post = 'POST';

    case Delete = 'DELETE';

    case Put = 'PUT';

    case Patch = 'PATCH';

    case Head = 'HEAD';

    case Options = 'OPTIONS';

    public function hasBody(): bool
    {
        return match ($this) {
            self::Get, self::Head, self::Options => false,
            default => true,
        };
    }
}
