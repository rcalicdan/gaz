<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Invoices\Exports;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Init\InitRequest;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Status\StatusRequest;

interface ExportsResourceInterface
{
    /**
     * @param InitRequest|array<string, mixed> $request
     */
    public function init(InitRequest | array $request): ResponseInterface;

    /**
     * @param StatusRequest|array<string, mixed> $request
     */
    public function status(StatusRequest | array $request): ResponseInterface;
}
