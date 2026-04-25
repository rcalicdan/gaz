<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface ParametersInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toParameters(): array;
}
