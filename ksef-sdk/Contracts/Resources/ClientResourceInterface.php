<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources;

use DateTimeInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Auth\AuthResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Certificates\CertificatesResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Invoices\InvoicesResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Latarnia\LatarniaResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Limits\LimitsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Peppol\PeppolResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\PermissionsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Security\SecurityResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\SessionsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\TestdataResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Tokens\TokensResourceInterface;
use Rcalicdan\KSEFClient\ValueObjects\AccessToken;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\RefreshToken;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;

/**
 * Main hub for the KSeF API endpoints.
 * Provides access to specific API resource groups (Auth, Sessions, Invoices, etc.).
 *
 * @api
 */
interface ClientResourceInterface
{
    /**
     * Get the current active access token.
     */
    public function getAccessToken(): ?AccessToken;

    /**
     * Get the current active refresh token.
     */
    public function getRefreshToken(): ?RefreshToken;

    /**
     * Set a new symmetric encryption key.
     */
    public function withEncryptionKey(EncryptionKey $encryptionKey): self;

    /**
     * Set the encrypted symmetric key and IV.
     */
    public function withEncryptedKey(EncryptedKey $encryptedKey): self;

    /**
     * Set the current access token.
     */
    public function withAccessToken(AccessToken | string $accessToken, DateTimeInterface | string | null $validUntil = null): self;

    /**
     * Set the current refresh token.
     */
    public function withRefreshToken(RefreshToken | string $refreshToken, DateTimeInterface | string | null $validUntil = null): self;

    /**
     * Access Authentication, Authorization, and Token management endpoints.
     */
    public function auth(): AuthResourceInterface;

    /**
     * Access system limits and usage contexts.
     */
    public function limits(): LimitsResourceInterface;

    /**
     * Get current KSeF API rate limits.
     * 
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function rateLimits(): ResponseInterface;

    /**
     * Access security endpoints (e.g., fetching KSeF public keys).
     */
    public function security(): SecurityResourceInterface;

    /**
     * Access Interactive (Online) and Batch session lifecycles.
     */
    public function sessions(): SessionsResourceInterface;

    /**
     * Access Invoice querying, downloading, and exports outside of an active session.
     */
    public function invoices(): InvoicesResourceInterface;

    /**
     * Access permissions management (granting/revoking access).
     */
    public function permissions(): PermissionsResourceInterface;

    /**
     * Access certificate management for the current context.
     */
    public function certificates(): CertificatesResourceInterface;

    /**
     * Access Peppol querying operations.
     */
    public function peppol(): PeppolResourceInterface;

    /**
     * Access endpoints to manage test data (Available only in test/demo environments).
     */
    public function testdata(): TestdataResourceInterface;

    /**
     * Access Latarnia endpoints (system availability and maintenance messages).
     */
    public function latarnia(): LatarniaResourceInterface;
}