<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Certificates\Enrollments;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Certificates\Enrollments\EnrollmentsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Data\DataHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Send\SendHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Send\SendRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Status\StatusHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Status\StatusRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class EnrollmentsResource extends AbstractResource implements EnrollmentsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function data(): ResponseInterface
    {
        try {
            return (new DataHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function send(SendRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof SendRequest === false) {
                $request = SendRequest::from($request, $this->valinorCache);
            }

            return (new SendHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof StatusRequest === false) {
                $request = StatusRequest::from($request, $this->valinorCache);
            }

            return (new StatusHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
