<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Query\Personal;

use CuyZ\Valinor\Cache\Cache;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Query\Personal\PersonalResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Personal\Grants\GrantsHandler;
use Rcalicdan\KSEFClient\Requests\Permissions\Query\Personal\Grants\GrantsRequest;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class PersonalResource extends AbstractResource implements PersonalResourceInterface
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
