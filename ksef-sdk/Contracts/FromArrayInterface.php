<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface FromArrayInterface extends FromInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function from(array $data): self;
}
