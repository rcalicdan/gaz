<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Context;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Block\BlockRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Context\Unblock\UnblockRequest;

interface ContextResourceInterface
{
    /**
     * @param BlockRequest|array<string, mixed> $request
     */
    public function block(BlockRequest | array $request): ResponseInterface;

    /**
     * @param UnblockRequest|array<string, mixed> $request
     */
    public function unblock(UnblockRequest | array $request): ResponseInterface;
}
