<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Open;

use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\Requests\AbstractHandler;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Method;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\Uri;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use RuntimeException;

final class OpenHandler extends AbstractHandler
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config
    ) {
    }

    public function handle(OpenRequest $request): ResponseInterface
    {
        if ($this->config->encryptedKey instanceof EncryptedKey === false) {
            throw new RuntimeException('Encrypted key is required to open session.');
        }

        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('sessions/online'),
            body: [
                ...$request->toBody(),
                'encryption' => [
                    'encryptedSymmetricKey' => $this->config->encryptedKey->key,
                    'initializationVector' => $this->config->encryptedKey->iv
                ]
            ]
        ));
    }
}
