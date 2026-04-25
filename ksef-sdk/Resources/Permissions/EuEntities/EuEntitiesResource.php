<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\EuEntities;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\EuEntities\EuEntitiesResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\EuEntities\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\EuEntities\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Permissions\EuEntities\Administration\AdministrationResource;
use Throwable;

final class EuEntitiesResource extends AbstractResource implements EuEntitiesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function administration(): AdministrationResource
    {
        try {
            return new AdministrationResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
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
}
