<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Auth\Sessions;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Auth\Sessions\List\ListRequest;
use Rcalicdan\KSEFClient\Requests\Auth\Sessions\Revoke\RevokeRequest;

interface SessionsResourceInterface
{
    /**
     * @param ListRequest|array<string, mixed> $request
     */
    public function list(ListRequest | array $request = []): ResponseInterface;

    public function revokeCurrent(): ResponseInterface;

    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;
}
