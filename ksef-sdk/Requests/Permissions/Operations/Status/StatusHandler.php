<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Operations\Status;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class StatusHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(StatusRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Get,
            uri: Uri::from(
                sprintf('permissions/operations/%s', $request->referenceNumber->value)
            )
        ));
    }
}
