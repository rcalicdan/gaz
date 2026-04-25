<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use InvalidArgumentException;
use Rcalicdan\KSEFClient\Contracts\EnumInterface;
use Rcalicdan\KSEFClient\Support\Concerns\HasEquals;

enum PrivateKeyType: string implements EnumInterface
{
    use HasEquals;

    case RSA = 'RSA';

    case EC = 'EC';

    public static function fromType(int $type): self
    {
        return match ($type) {
            OPENSSL_KEYTYPE_RSA => self::RSA,
            OPENSSL_KEYTYPE_EC => self::EC,
            default => throw new InvalidArgumentException('Unknown key type')
        };
    }

    /**
     * @return array<string, int | string>
     */
    public function getOptions(): array
    {
        return match ($this) {
            self::RSA => [
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ],
            self::EC => [
                'private_key_type' => OPENSSL_KEYTYPE_EC,
                'curve_name' => 'prime256v1'
            ]
        };
    }
}
