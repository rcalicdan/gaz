<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Failed;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class FailedHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(FailedRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('sessions/%s/invoices/failed', $request->referenceNumber->value)
            ),
            headers: $request->toHeaders(),
            parameters: $request->toParameters(),
        ));
    }
}
