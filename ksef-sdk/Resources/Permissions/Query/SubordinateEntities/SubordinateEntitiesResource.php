<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Query\SubordinateEntities;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\SubordinateEntities\SubordinateEntitiesResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\SubordinateEntities\Roles\RolesRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SubordinateEntitiesResource extends AbstractResource implements SubordinateEntitiesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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
