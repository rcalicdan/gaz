<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Context\Block;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class BlockHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function handle(BlockRequest $request): ResponseInterface
    {
        return $this->client
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Post,
                uri: Uri::from('testdata/context/block'),
                body: $request->toBody()
            ));
    }
}
