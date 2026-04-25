<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Close;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;

final class CloseHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(CloseRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from(
                sprintf('sessions/online/%s/close', $request->referenceNumber->value)
            ),
        ));
    }
}
