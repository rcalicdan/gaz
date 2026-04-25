<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Peppol;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Peppol\PeppolResourceInterface;
use Rcalicdan\KSEFClient\Requests\Peppol\Query\QueryHandler;
use Rcalicdan\KSEFClient\Requests\Peppol\Query\QueryRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class PeppolResource extends AbstractResource implements PeppolResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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
}
