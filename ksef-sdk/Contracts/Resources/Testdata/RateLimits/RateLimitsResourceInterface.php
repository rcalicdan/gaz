<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\RateLimits;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\RateLimits\Limits\LimitsRequest;

interface RateLimitsResourceInterface
{
    /**
     * @param LimitsRequest|array<string, mixed> $request
     */
    public function limits(LimitsRequest | array $request): ResponseInterface;

    public function reset(): ResponseInterface;

    public function production(): ResponseInterface;
}
