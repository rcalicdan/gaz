<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\HttpClient;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\DTOs\HttpClient\Request;
use Rcalicdan\KSEFClient\ValueObjects\AccessToken;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\BaseUri;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;

/**
 * Core HTTP Client abstraction for KSeF API communication.
 * Handles request building, authorization injection, and synchronous/asynchronous execution.
 *
 * @api
 */
interface HttpClientInterface
{
    /**
     * Sends a synchronous HTTP request to the KSeF API.
     *
     * @param Request $request The pre-configured request DTO.
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception Thrown on API errors (4xx, 5xx status codes).
     */
    public function sendRequest(Request $request): ResponseInterface;

    /**
     * Sends multiple HTTP requests asynchronously (concurrently) to the KSeF API.
     * Highly useful for batch operations, like uploading multiple encrypted parts of a large ZIP archive.
     *
     * @param array<int, Request> $requests Array of configured requests.
     * @return array<int, ResponseInterface|null> Array of responses matching the original request keys.
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function sendAsyncRequest(array $requests): array;

    /**
     * Returns a new client instance configured with the specified Base URI.
     * 
     * @param BaseUri $baseUri
     * @return self
     */
    public function withBaseUri(BaseUri $baseUri): self;

    /**
     * Returns a new client instance configured with an Access Token.
     * The token will automatically be injected into the `Authorization: Bearer` header.
     * 
     * @param AccessToken $accessToken
     * @return self
     */
    public function withAccessToken(AccessToken $accessToken): self;

    /**
     * Returns a new client instance explicitly stripped of the Access Token.
     * Useful for endpoints like `auth/challenge` that reject requests if an auth header is present.
     * 
     * @return self
     */
    public function withoutAccessToken(): self;

    /**
     * Returns a new client instance configured with a raw AES symmetric encryption key.
     * Used locally to encrypt invoice payloads before transmission.
     * 
     * @param EncryptionKey $encryptionKey
     * @return self
     */
    public function withEncryptionKey(EncryptionKey $encryptionKey): self;

    /**
     * Returns a new client instance configured with an RSA-encrypted AES symmetric key.
     * Passed to KSeF during session initialization so the server can decrypt payloads.
     * 
     * @param EncryptedKey $encryptedKey
     * @return self
     */
    public function withEncryptedKey(EncryptedKey $encryptedKey): self;
}