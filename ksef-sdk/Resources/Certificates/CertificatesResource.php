<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Certificates;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Certificates\CertificatesResourceInterface;
use Rcalicdan\KSEFClient\Requests\Certificates\Limits\LimitsHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Query\QueryHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Query\QueryRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Retrieve\RetrieveHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Retrieve\RetrieveRequest;
use Rcalicdan\KSEFClient\Requests\Certificates\Revoke\RevokeHandler;
use Rcalicdan\KSEFClient\Requests\Certificates\Revoke\RevokeRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Certificates\Enrollments\EnrollmentsResource;
use Throwable;

final class CertificatesResource extends AbstractResource implements CertificatesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function limits(): ResponseInterface
    {
        try {
            return (new LimitsHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function enrollments(): EnrollmentsResource
    {
        try {
            return new EnrollmentsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function query(QueryRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof QueryRequest === false) {
                $request = QueryRequest::from($request, $this->valinorCache);
            }

            return (new QueryHandler($this->client))->handle($request);
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

    public function retrieve(RetrieveRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RetrieveRequest === false) {
                $request = RetrieveRequest::from($request, $this->valinorCache);
            }

            return (new RetrieveHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
