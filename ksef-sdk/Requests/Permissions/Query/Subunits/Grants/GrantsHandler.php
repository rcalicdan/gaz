<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\Subunits\Grants;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class GrantsHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(GrantsRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('permissions/query/subunits/grants'),
            body: $request->toBody()
        ));
    }
}
