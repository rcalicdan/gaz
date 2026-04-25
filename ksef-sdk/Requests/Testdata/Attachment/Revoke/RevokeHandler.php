<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Attachment\Revoke;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class RevokeHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(RevokeRequest $request): ResponseInterface
    {
        return $this->client
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Post,
                uri: Uri::from('testdata/attachment/revoke'),
                body: $request->toBody()
            ));
    }
}
