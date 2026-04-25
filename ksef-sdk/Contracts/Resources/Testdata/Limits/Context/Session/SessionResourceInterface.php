<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Context\Session;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits\LimitsRequest;

interface SessionResourceInterface
{
    /**
     * @param LimitsRequest|array<string, mixed> $request
     */
    public function limits(LimitsRequest | array $request): ResponseInterface;

    public function reset(): ResponseInterface;
}
