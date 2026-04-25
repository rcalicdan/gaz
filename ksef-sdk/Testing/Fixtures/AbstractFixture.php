<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures;

/**
 * @property string|array<string, mixed> $data
 */
abstract class AbstractFixture
{
    public string $name = 'default';

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
