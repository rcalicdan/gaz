<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Retrieve;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;

final class RetrieveHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(RetrieveRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('certificates/retrieve'),
            body: $request->toBody()
        ));
    }
}
