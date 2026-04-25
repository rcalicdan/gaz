<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Limits;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\LimitsResourceInterface;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Limits\Context\ContextResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Limits\Subject\SubjectResource;
use Throwable;

final class LimitsResource extends AbstractResource implements LimitsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function context(): ContextResource
    {
        try {
            return new ContextResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function subject(): SubjectResource
    {
        try {
            return new SubjectResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
