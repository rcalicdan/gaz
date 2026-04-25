<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface BodyInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toBody(): array;
}
