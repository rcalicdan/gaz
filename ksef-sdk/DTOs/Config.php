<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs;

use Rcalicdan\KSEFClient\Contracts\ConfigInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\AccessToken;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\BaseUri;
use Rcalicdan\KSEFClient\ValueObjects\Mode;
use Rcalicdan\KSEFClient\ValueObjects\RefreshToken;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;

final class Config extends AbstractDTO implements ConfigInterface
{
    public function __construct(
        public readonly Mode $mode,
        public readonly BaseUri $baseUri,
        public readonly BaseUri $latarniaBaseUri,
        public readonly int $asyncMaxConcurrency = 8,
        public readonly bool $validateXml = true,
        public readonly int $cacheTTL = 43200,
        public readonly ?AccessToken $accessToken = null,
        public readonly ?RefreshToken $refreshToken = null,
        public readonly ?EncryptionKey $encryptionKey = null,
        public readonly ?EncryptedKey $encryptedKey = null
    ) {
    }

    public function withBaseUri(BaseUri $baseUri): self
    {
        return $this->with([
            'baseUri' => $baseUri
        ]);
    }

    public function withEncryptionKey(EncryptionKey $encryptionKey): self
    {
        return $this->with([
            'encryptionKey' => $encryptionKey
        ]);
    }

    public function withEncryptedKey(EncryptedKey $encryptedKey): self
    {
        return $this->with([
            'encryptedKey' => $encryptedKey
        ]);
    }

    public function withAccessToken(AccessToken $accessToken): self
    {
        return $this->with([
            'accessToken' => $accessToken
        ]);
    }

    public function withoutAccessToken(): self
    {
        return $this->with([
            'accessToken' => null
        ]);
    }

    public function withRefreshToken(RefreshToken $refreshToken): self
    {
        return $this->with([
            'refreshToken' => $refreshToken
        ]);
    }
}
