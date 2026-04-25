<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Peppol;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Peppol\Query\QueryRequest;

interface PeppolResourceInterface
{
    /**
     * @param QueryRequest|array<string, mixed> $request
     */
    public function query(QueryRequest | array $request): ResponseInterface;
}
