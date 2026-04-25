<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Operations;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Operations\Status\StatusRequest;

interface OperationsResourceInterface
{
    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): ResponseInterface;
}
