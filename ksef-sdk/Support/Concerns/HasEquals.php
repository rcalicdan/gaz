<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Support\Concerns;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;

/**
 * @mixin ValueAwareInterface
 */
trait HasEquals
{
    public function isEquals(ValueAwareInterface $value): bool
    {
        return $this->value === $value->value;
    }
}
