<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\PermissionsResourceInterface;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Attachments\AttachmentsResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Authorizations\AuthorizationsResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Common\CommonResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Entities\EntitiesResource;
use Rcalicdan\KSEFClient\Resources\Permissions\EuEntities\EuEntitiesResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Indirect\IndirectResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Operations\OperationsResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Persons\PersonsResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Query\QueryResource;
use Rcalicdan\KSEFClient\Resources\Permissions\Subunits\SubunitsResource;
use Throwable;

final class PermissionsResource extends AbstractResource implements PermissionsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function common(): CommonResource
    {
        try {
            return new CommonResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function persons(): PersonsResource
    {
        try {
            return new PersonsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function entities(): EntitiesResource
    {
        try {
            return new EntitiesResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function authorizations(): AuthorizationsResource
    {
        try {
            return new AuthorizationsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function indirect(): IndirectResource
    {
        try {
            return new IndirectResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function subunits(): SubunitsResource
    {
        try {
            return new SubunitsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function euEntities(): EuEntitiesResource
    {
        try {
            return new EuEntitiesResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function operations(): OperationsResource
    {
        try {
            return new OperationsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function query(): QueryResource
    {
        try {
            return new QueryResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function attachments(): AttachmentsResource
    {
        try {
            return new AttachmentsResource($this->client, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
