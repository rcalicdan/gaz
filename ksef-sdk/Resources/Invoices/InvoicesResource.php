<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Invoices;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Invoices\InvoicesResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Invoices\Download\DownloadHandler;
use Rcalicdan\KSEFClient\Requests\Invoices\Download\DownloadRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Invoices\Exports\ExportsResource;
use Rcalicdan\KSEFClient\Resources\Invoices\Query\QueryResource;
use Throwable;

final class InvoicesResource extends AbstractResource implements InvoicesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function download(DownloadRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof DownloadRequest === false) {
                $request = DownloadRequest::from($request, $this->valinorCache);
            }

            return (new DownloadHandler($this->client))->handle($request);
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

    public function exports(): ExportsResource
    {
        try {
            return new ExportsResource($this->client, $this->config, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
