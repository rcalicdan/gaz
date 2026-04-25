<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\TestdataResourceInterface;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Attachment\AttachmentResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Context\ContextResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Limits\LimitsResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Permissions\PermissionsResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Person\PersonResource;
use Rcalicdan\KSEFClient\Resources\Testdata\RateLimits\RateLimitsResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Subject\SubjectResource;
use Throwable;

final class TestdataResource extends AbstractResource implements TestdataResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function subject(): SubjectResource
    {
        try {
            return new SubjectResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function person(): PersonResource
    {
        try {
            return new PersonResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function limits(): LimitsResource
    {
        try {
            return new LimitsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function rateLimits(): RateLimitsResource
    {
        try {
            return new RateLimitsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function attachment(): AttachmentResource
    {
        try {
            return new AttachmentResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function context(): ContextResource
    {
        try {
            return new ContextResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function permissions(): PermissionsResource
    {
        try {
            return new PermissionsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
