<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Subject\Create;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class CreateHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(CreateRequest $request): ResponseInterface
    {
        return $this->client
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Post,
                uri: Uri::from('testdata/subject'),
                body: $request->toBody()
            ));
    }
}
