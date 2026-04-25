<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface WithInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function with(array $data): self;
}
