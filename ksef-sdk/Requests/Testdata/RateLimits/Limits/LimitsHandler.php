<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\RateLimits\Limits;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class LimitsHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(LimitsRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('testdata/rate-limits'),
            body: $request->toBody()
        ));
    }
}
