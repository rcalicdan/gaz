<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Limits;

use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Limits\LimitsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Limits\Context\ContextHandler;
use Rcalicdan\KSEFClient\Requests\Limits\Subject\SubjectHandler;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class LimitsResource extends AbstractResource implements LimitsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function context(): ResponseInterface
    {
        try {
            return (new ContextHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function subject(): ResponseInterface
    {
        try {
            return (new SubjectHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
