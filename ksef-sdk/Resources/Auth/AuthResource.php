<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Auth;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use Rcalicdan\KSEFClient\Actions\SignDocument\SignDocumentHandler;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Auth\AuthResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Auth\Challenge\ChallengeHandler;
use Rcalicdan\KSEFClient\Requests\Auth\Status\StatusHandler;
use Rcalicdan\KSEFClient\Requests\Auth\Status\StatusRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureHandler;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureXmlRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Auth\Sessions\SessionsResource;
use Rcalicdan\KSEFClient\Resources\Auth\Token\TokenResource;
use Throwable;

final class AuthResource extends AbstractResource implements AuthResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function challenge(): ResponseInterface
    {
        try {
            return (new ChallengeHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function xadesSignature(XadesSignatureRequest | XadesSignatureXmlRequest | array $request): ResponseInterface
    {
        try {
            if (is_array($request)) {
                $request = XadesSignatureRequest::from($request, $this->valinorCache);
            }

            return (new XadesSignatureHandler(
                client: $this->client,
                signDocument: new SignDocumentHandler(new ConvertEcdsaDerToRawHandler()),
                config: $this->config
            ))->handle($request);
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

    public function token(): TokenResource
    {
        try {
            return new TokenResource($this->client, $this->config, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function sessions(): SessionsResource
    {
        try {
            return new SessionsResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
