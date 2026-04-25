<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Authorizations;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeRequest;

interface AuthorizationsResourceInterface
{
    /**
     * @param GrantsRequest|array<string, mixed> $request
     */
    public function grants(GrantsRequest | array $request): ResponseInterface;

    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;
}
