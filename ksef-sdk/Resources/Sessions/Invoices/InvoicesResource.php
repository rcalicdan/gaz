<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Sessions\Invoices;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Invoices\InvoicesResourceInterface;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Failed\FailedHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Failed\FailedRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\KsefUpo\KsefUpoHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\KsefUpo\KsefUpoRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\List\ListHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\List\ListRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Status\StatusHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Status\StatusRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Upo\UpoHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Upo\UpoRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class InvoicesResource extends AbstractResource implements InvoicesResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
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

    public function list(ListRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof ListRequest === false) {
                $request = ListRequest::from($request, $this->valinorCache);
            }

            return (new ListHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function failed(FailedRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof FailedRequest === false) {
                $request = FailedRequest::from($request, $this->valinorCache);
            }

            return (new FailedHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function ksefUpo(KsefUpoRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof KsefUpoRequest === false) {
                $request = KsefUpoRequest::from($request, $this->valinorCache);
            }

            return (new KsefUpoHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function upo(UpoRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof UpoRequest === false) {
                $request = UpoRequest::from($request, $this->valinorCache);
            }

            return (new UpoHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
