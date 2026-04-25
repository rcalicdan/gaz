<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Revoke\RevokeRequest;

interface PermissionsResourceInterface
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
