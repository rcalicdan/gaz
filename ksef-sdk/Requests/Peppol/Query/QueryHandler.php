<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Peppol\Query;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class QueryHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(QueryRequest $request): ResponseInterface
    {
        return $this->client
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Get,
                uri: Uri::from('peppol/query'),
                parameters: $request->toParameters()
            ));
    }
}
