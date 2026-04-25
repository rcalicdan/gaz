<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\RateLimits\Reset;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class ResetHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Delete,
            uri: Uri::from('testdata/rate-limits')
        ));
    }
}
