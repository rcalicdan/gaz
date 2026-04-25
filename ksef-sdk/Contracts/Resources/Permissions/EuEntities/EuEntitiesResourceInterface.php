<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\EuEntities;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\EuEntities\Administration\AdministrationResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\EuEntities\Grants\GrantsRequest;

interface EuEntitiesResourceInterface
{
    public function administration(): AdministrationResourceInterface;

    /**
     * @param GrantsRequest|array<string, mixed> $request
     */
    public function grants(GrantsRequest | array $request): ResponseInterface;
}
