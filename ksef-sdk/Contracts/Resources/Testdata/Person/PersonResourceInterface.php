<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Person;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Person\Create\CreateRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Person\Remove\RemoveRequest;

interface PersonResourceInterface
{
    /**
     * @param CreateRequest|array<string, mixed> $request
     */
    public function create(CreateRequest | array $request): ResponseInterface;

    /**
     * @param RemoveRequest|array<string, mixed> $request
     */
    public function remove(RemoveRequest | array $request): ResponseInterface;
}
