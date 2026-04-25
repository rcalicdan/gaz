<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Persons;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Persons\Grants\GrantsRequest;

interface PersonsResourceInterface
{
    /**
     * @param GrantsRequest|array<string, mixed> $request
     */
    public function grants(GrantsRequest | array $request): ResponseInterface;
}
