<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Testdata\Limits\Subject;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject\SubjectResourceInterface;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Rcalicdan\KSEFClient\Resources\Testdata\Limits\Subject\Certificate\CertificateResource;
use Throwable;

final class SubjectResource extends AbstractResource implements SubjectResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function certificate(): CertificateResource
    {
        try {
            return new CertificateResource($this->client, $this->exceptionHandler, $this->valinorCache);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
