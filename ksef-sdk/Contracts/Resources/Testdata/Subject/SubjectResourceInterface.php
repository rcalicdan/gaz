<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Subject;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Create\CreateRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Subject\Remove\RemoveRequest;

interface SubjectResourceInterface
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
