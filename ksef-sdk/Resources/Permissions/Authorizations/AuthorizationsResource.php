<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Authorizations;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Authorizations\AuthorizationsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Revoke\RevokeRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AuthorizationsResource extends AbstractResource implements AuthorizationsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function grants(GrantsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof GrantsRequest === false) {
                $request = GrantsRequest::from($request, $this->valinorCache);
            }

            return (new GrantsHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function revoke(RevokeRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RevokeRequest === false) {
                $request = RevokeRequest::from($request, $this->valinorCache);
            }

            return (new RevokeHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
