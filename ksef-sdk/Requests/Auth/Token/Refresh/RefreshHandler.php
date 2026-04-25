<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\Token\Refresh;

use InvalidArgumentException;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\AccessToken;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\ValueObjects\RefreshToken;

final class RefreshHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config
    ) {
    }

    public function handle(): ResponseInterface
    {
        if ( ! $this->config->refreshToken instanceof RefreshToken) {
            throw new InvalidArgumentException('Refresh token is empty');
        }

        return $this->client
            ->withAccessToken(AccessToken::from($this->config->refreshToken->token))
            ->sendRequest(new Request(
                method: Method::Post,
                uri: Uri::from('auth/token/refresh')
            ));
    }
}
