<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Common;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Common\CommonResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Common\Revoke\RevokeHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Common\Revoke\RevokeRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class CommonResource extends AbstractResource implements CommonResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function revoke(RevokeRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof RevokeRequest === false) {
                $request = RevokeRequest::from($request, $this->valinorCache);
            }

            return (new RevokeHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
