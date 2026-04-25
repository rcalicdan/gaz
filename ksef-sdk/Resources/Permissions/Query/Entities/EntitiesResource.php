<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Query\Entities;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\Entities\EntitiesResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Roles\RolesRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class EntitiesResource extends AbstractResource implements EntitiesResourceInterface
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

    public function roles(RolesRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RolesRequest === false) {
                $request = RolesRequest::from($request, $this->valinorCache);
            }

            return (new RolesHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
