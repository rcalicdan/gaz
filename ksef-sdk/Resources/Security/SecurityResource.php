<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Security;

use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Security\SecurityResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesHandler;
use Rcalicdan\KSEFClient\Requests\Security\PublicKeyCertificates\PublicKeyCertificatesResponse;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Psr\SimpleCache\CacheInterface;
use Throwable;

final class SecurityResource extends AbstractResource implements SecurityResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?CacheInterface $cache = null
    ) {
    }

    public function publicKeyCertificates(): PublicKeyCertificatesResponse
    {
        try {
            /** @var PublicKeyCertificatesResponse */
            return (new PublicKeyCertificatesHandler($this->client, $this->config, $this->cache))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
