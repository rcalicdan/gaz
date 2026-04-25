<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Certificates\Enrollments;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Send\SendRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Status\StatusRequest;

interface EnrollmentsResourceInterface
{
    public function data(): ResponseInterface;

    /**
     * @param SendRequest|array<string, mixed> $request
     */
    public function send(SendRequest | array $request): ResponseInterface;

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): ResponseInterface;
}
