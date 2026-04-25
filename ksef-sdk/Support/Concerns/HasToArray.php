<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Support\Concerns;

use Rcalicdan\KSEFClient\Support\Arr;
use Rcalicdan\KSEFClient\ValueObjects\Support\KeyType;

trait HasToArray
{
    /**
     * @param array<int, string> $only
     * @return array<string|int, mixed>
     */
    public function toArray(
        KeyType $keyType = KeyType::Camel,
        array $keyTypeExcept = ['p_', 'uu_id'],
        array $only = []
    ): array {
        return Arr::normalize(get_object_vars($this), $keyType, $keyTypeExcept, $only);
    }
}
