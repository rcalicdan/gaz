<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Permissions;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Permissions\PermissionsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Revoke\RevokeHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Revoke\RevokeRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class PermissionsResource extends AbstractResource implements PermissionsResourceInterface
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
