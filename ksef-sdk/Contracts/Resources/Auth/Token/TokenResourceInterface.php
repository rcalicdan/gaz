<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Auth\Token;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;

interface TokenResourceInterface
{
    public function redeem(): ResponseInterface;

    public function refresh(): ResponseInterface;
}
