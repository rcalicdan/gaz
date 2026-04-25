<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface EqualsInterface
{
    public function isEquals(ValueAwareInterface $value): bool;
}
