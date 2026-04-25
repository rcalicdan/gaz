<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Common;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Common\Revoke\RevokeRequest;

interface CommonResourceInterface
{
    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;
}
