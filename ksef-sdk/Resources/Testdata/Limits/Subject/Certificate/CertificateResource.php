<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Limits\Subject\Certificate;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject\Certificate\CertificateResourceInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Limits\LimitsHandler;
use Rcalicdan\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Limits\LimitsRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Reset\ResetHandler;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class CertificateResource extends AbstractResource implements CertificateResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function limits(LimitsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof LimitsRequest === false) {
                $request = LimitsRequest::from($request, $this->valinorCache);
            }

            return (new LimitsHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function reset(): ResponseInterface
    {
        try {
            return (new ResetHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
