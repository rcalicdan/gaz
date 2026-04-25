<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\SubordinateEntities;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesRequest;

interface SubordinateEntitiesResourceInterface
{
    /**
     * @param RolesRequest|array<string, mixed> $request
     */
    public function roles(RolesRequest | array $request): ResponseInterface;
}
