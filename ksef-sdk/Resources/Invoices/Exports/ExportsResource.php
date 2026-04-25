<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Invoices\Exports;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Invoices\Exports\ExportsResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Init\InitHandler;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Init\InitRequest;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Status\StatusHandler;
use Rcalicdan\KSEFClient\Requests\Invoices\Exports\Status\StatusRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class ExportsResource extends AbstractResource implements ExportsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function init(InitRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof InitRequest === false) {
                $request = InitRequest::from($request, $this->valinorCache);
            }

            return (new InitHandler($this->client, $this->config))->handle($request);
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
