<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts;

interface PipelineInterface
{
    /**
     * @param  array<int, PipeInterface> $pipes
     */
    public function through(array $pipes): self;

    public function pipe(PipeInterface $pipe): self;

    public function via(string $method): self;

    public function process(mixed $traveler): mixed;
}
