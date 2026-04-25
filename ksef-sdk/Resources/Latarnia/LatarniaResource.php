<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Latarnia;

use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Latarnia\LatarniaResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Latarnia\Messages\MessagesHandler;
use Rcalicdan\KSEFClient\Requests\Latarnia\Status\StatusHandler;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class LatarniaResource extends AbstractResource implements LatarniaResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function status(): ResponseInterface
    {
        try {
            return (new StatusHandler($this->client, $this->config))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function messages(): ResponseInterface
    {
        try {
            return (new MessagesHandler($this->client, $this->config))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
