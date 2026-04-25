<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Factories;

use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;

final class EncryptionKeyFactory extends AbstractFactory
{
    public static function makeRandom(): EncryptionKey
    {
        return new EncryptionKey(random_bytes(32), random_bytes(16));
    }
}
