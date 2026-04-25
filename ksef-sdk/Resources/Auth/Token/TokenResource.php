<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Auth\Token;

use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Auth\Token\TokenResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Auth\Token\Redeem\RedeemHandler;
use Rcalicdan\KSEFClient\Requests\Auth\Token\Refresh\RefreshHandler;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class TokenResource extends AbstractResource implements TokenResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
    ) {
    }

    public function redeem(): ResponseInterface
    {
        try {
            return (new RedeemHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function refresh(): ResponseInterface
    {
        try {
            return (new RefreshHandler($this->client, $this->config))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
