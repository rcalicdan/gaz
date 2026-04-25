<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Certificates;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Certificates\Enrollments\EnrollmentsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Certificates\Query\QueryRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Retrieve\RetrieveRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Revoke\RevokeRequest;

interface CertificatesResourceInterface
{
    public function limits(): ResponseInterface;

    public function enrollments(): EnrollmentsResourceInterface;

    /**
     * @param QueryRequest|array<string, mixed> $request
     */
    public function query(QueryRequest | array $request): ResponseInterface;

    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;

    /**
     * @param RetrieveRequest|array<string, mixed> $request
     */
    public function retrieve(RetrieveRequest | array $request): ResponseInterface;
}
