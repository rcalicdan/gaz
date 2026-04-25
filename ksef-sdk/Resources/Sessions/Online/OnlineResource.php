<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Sessions\Online;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Actions\EncryptDocument\EncryptDocumentHandler;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Online\OnlineResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Close\CloseHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Open\OpenHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Send\SendHandler;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Send\SendRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Send\SendXmlRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class OnlineResource extends AbstractResource implements OnlineResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function open(OpenRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof OpenRequest === false) {
                $request = OpenRequest::from($request, $this->valinorCache);
            }

            return (new OpenHandler($this->client, $this->config))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function close(CloseRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof CloseRequest === false) {
                $request = CloseRequest::from($request, $this->valinorCache);
            }

            return (new CloseHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function send(SendRequest | SendXmlRequest | array $request): ResponseInterface
    {
        try {
            if (is_array($request)) {
                $request = SendRequest::from($request, $this->valinorCache);
            }

            return (new SendHandler(
                client: $this->client,
                encryptDocument: new EncryptDocumentHandler($this->logger),
                config: $this->config
            ))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
