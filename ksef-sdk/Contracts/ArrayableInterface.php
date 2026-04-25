<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

use Rcalicdan\KSEFClient\ValueObjects\Support\KeyType;

interface ArrayableInterface
{
    /**
     * @param array<int, string> $only
     * @return array<string, mixed>
     */
    public function toArray(KeyType $keyType = KeyType::Camel, array $only = []): array;
}
