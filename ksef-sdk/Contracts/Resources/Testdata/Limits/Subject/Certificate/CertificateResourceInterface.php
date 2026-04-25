<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject\Certificate;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Limits\LimitsRequest;

interface CertificateResourceInterface
{
    /**
     * @param LimitsRequest|array<string, mixed> $request
     */
    public function limits(LimitsRequest | array $request): ResponseInterface;

    public function reset(): ResponseInterface;
}
