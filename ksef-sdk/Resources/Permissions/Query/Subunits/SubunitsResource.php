<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Query\Subunits;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\Subunits\SubunitsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Subunits\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Subunits\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class SubunitsResource extends AbstractResource implements SubunitsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?Cache $valinorCache = null
    ) {
    }

    public function grants(GrantsRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof GrantsRequest === false) {
                $request = GrantsRequest::from($request, $this->valinorCache);
            }

            return (new GrantsHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
