<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Latarnia\Messages;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;

final class MessagesHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config
    ) {
    }

    public function handle(): ResponseInterface
    {
        return $this->client
            ->withBaseUri($this->config->latarniaBaseUri)
            ->withoutAccessToken()
            ->sendRequest(new Request(
                method: Method::Get,
                uri: Uri::from('messages')
            ));
    }
}
