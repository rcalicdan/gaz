<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\Entities;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesRequest;

interface EntitiesResourceInterface
{
    /**
     * @param GrantsRequest|array<string, mixed> $request
     */
    public function grants(GrantsRequest | array $request): ResponseInterface;

    /**
     * @param RolesRequest|array<string, mixed> $request
     */
    public function roles(RolesRequest | array $request): ResponseInterface;
}
