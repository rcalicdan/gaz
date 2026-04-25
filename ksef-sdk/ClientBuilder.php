<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient;

use CuyZ\Valinor\Cache\Cache;
use DateTimeImmutable;
use DateTimeInterface;
use Http\Discovery\Psr18ClientDiscovery;
use InvalidArgumentException;
use Rcalicdan\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemAction;
use Rcalicdan\KSEFClient\Actions\ConvertDerToPem\ConvertDerToPemHandler;
use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\ClientResourceInterface;
use Rcalicdan\KSEFClient\DTOs\Config;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\XadesSignature;
use Rcalicdan\KSEFClient\Exceptions\ExceptionHandler;
use Rcalicdan\KSEFClient\Exceptions\StatusException;
use Rcalicdan\KSEFClient\Factories\CertificateFactory;
use Rcalicdan\KSEFClient\Factories\ClientFactory;
use Rcalicdan\KSEFClient\Factories\EncryptedKeyFactory;
use Rcalicdan\KSEFClient\Factories\LoggerFactory;
use Rcalicdan\KSEFClient\HttpClient\HttpClient;
use Rcalicdan\KSEFClient\Requests\Auth\Status\StatusRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use Rcalicdan\KSEFClient\Resources\ClientResource;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\Support\Utility;
use Rcalicdan\KSEFClient\ValueObjects\AccessToken;
use Rcalicdan\KSEFClient\ValueObjects\ApiUrl;
use Rcalicdan\KSEFClient\ValueObjects\Certificate;
use Rcalicdan\KSEFClient\ValueObjects\CertificatePath;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\HttpClient\BaseUri;
use Rcalicdan\KSEFClient\ValueObjects\InternalId;
use Rcalicdan\KSEFClient\ValueObjects\KsefPublicKey;
use Rcalicdan\KSEFClient\ValueObjects\LogPath;
use Rcalicdan\KSEFClient\ValueObjects\Mode;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\NipVatUe;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;
use Rcalicdan\KSEFClient\ValueObjects\RefreshToken;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Auth\Challenge;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Auth\SubjectIdentifierType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Security\PublicKeyCertificates\PublicKeyCertificateUsage;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use Psr\Http\Client\ClientInterface as BaseClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use SensitiveParameter;
use Throwable;

/**
 * Fluent builder for configuring and creating the KSeF API Client.
 *
 * @api
 */
final class ClientBuilder
{
    private ClientInterface $httpClient;

    private ?LoggerInterface $logger = null;

    private ExceptionHandlerInterface $exceptionHandler;

    private ?CacheInterface $cache = null;

    private ?Cache $valinorCache = null;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ApiUrl $latarniaApiUrl;

    private ?AccessToken $accessToken = null;

    private ?RefreshToken $refreshToken = null;

    private ?Certificate $certificate = null;

    private NIP | NipVatUe | InternalId | PeppolId $identifier;

    private ?EncryptionKey $encryptionKey = null;

    private Optional | bool $verifyCertificateChain;

    private int $asyncMaxConcurrency = 8;

    private bool $validateXml = true;

    private int $cacheTTL = 43200;

    public function __construct()
    {
        $this->httpClient = ClientFactory::make(Psr18ClientDiscovery::find());
        $this->logger = LoggerFactory::make();
        $this->apiUrl = $this->mode->getApiUrl();
        $this->latarniaApiUrl = $this->mode->getLatarniaApiUrl();
        $this->verifyCertificateChain = new Optional();
    }

    /**
     * Sets the environment mode (Test, Demo, or Production).
     * This automatically configures the base API URLs for KSeF.
     *
     * @param Mode|string $mode
     * @return static
     */
    public function withMode(Mode | string $mode): self
    {
        if ($mode instanceof Mode === false) {
            $mode = Mode::from($mode);
        }

        $this->mode = $mode;

        $this->apiUrl = $this->mode->getApiUrl();
        $this->latarniaApiUrl = $this->mode->getLatarniaApiUrl();

        if ($this->mode->isEquals(Mode::Test)) {
            $this->identifier = new NIP('1111111111');
        }

        return $this;
    }

    /**
     * Sets the symmetric encryption key used for encrypting invoice payloads.
     *
     * @param EncryptionKey|string $encryptionKey
     * @param string|null $iv
     * @return static
     */
    public function withEncryptionKey(#[SensitiveParameter] EncryptionKey | string $encryptionKey, #[SensitiveParameter] ?string $iv = null): self
    {
        if (is_string($encryptionKey)) {
            if ($iv === null) {
                throw new InvalidArgumentException('IV is required when key is string.');
            }

            $encryptionKey = new EncryptionKey($encryptionKey, $iv);
        }

        $this->encryptionKey = $encryptionKey;

        return $this;
    }

    /**
     * Overrides the default KSeF API Base URL.
     *
     * @param ApiUrl|string $apiUrl
     * @return static
     */
    public function withApiUrl(ApiUrl | string $apiUrl): self
    {
        if ($apiUrl instanceof ApiUrl === false) {
            $apiUrl = ApiUrl::from($apiUrl);
        }

        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Overrides the default Latarnia (status/messages) Base URL.
     *
     * @param ApiUrl|string $latarniaApiUrl
     * @return static
     */
    public function withLatarniaApiUrl(ApiUrl | string $latarniaApiUrl): self
    {
        if ($latarniaApiUrl instanceof ApiUrl === false) {
            $latarniaApiUrl = ApiUrl::from($latarniaApiUrl);
        }

        $this->latarniaApiUrl = $latarniaApiUrl;

        return $this;
    }

    /**
     * Sets a pre-existing Access Token to skip the authentication challenge process.
     *
     * @param AccessToken|string $accessToken
     * @param DateTimeInterface|string|null $validUntil
     * @return static
     */
    public function withAccessToken(#[SensitiveParameter] AccessToken | string $accessToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($accessToken instanceof AccessToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $accessToken = AccessToken::from($accessToken, $validUntil);
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Sets a pre-existing Refresh Token.
     *
     * @param RefreshToken|string $refreshToken
     * @param DateTimeInterface|string|null $validUntil
     * @return static
     */
    public function withRefreshToken(#[SensitiveParameter] RefreshToken | string $refreshToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($refreshToken instanceof RefreshToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $refreshToken = RefreshToken::from($refreshToken, $validUntil);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Set a digital certificate using a file path (e.g., PKCS#12 file).
     *
     * @param CertificatePath|string $certificatePath
     * @param string|null $passphrase
     * @return static
     */
    public function withCertificatePath(CertificatePath | string $certificatePath, #[SensitiveParameter] ?string $passphrase = null): self
    {
        if ($certificatePath instanceof CertificatePath === false) {
            $certificatePath = CertificatePath::from($certificatePath, $passphrase);
        }

        $certificate = CertificateFactory::makeFromCertificatePath($certificatePath);

        return $this->withCertificate($certificate);
    }

    /**
     * Authenticate using a digital certificate (e.g., PKCS#12, PEM).
     * The builder will automatically perform the XAdES signature challenge/response
     * during the build() step to fetch an access token.
     *
     * @param Certificate|string $certificate
     * @param string|null $privateKey Required if $certificate is a PEM string.
     * @param string|null $passphrase
     * @return static
     */
    public function withCertificate(Certificate | string $certificate, #[SensitiveParameter] ?string $privateKey = null, #[SensitiveParameter] ?string $passphrase = null): self
    {
        if ($certificate instanceof Certificate === false) {
            if ($privateKey === null) {
                throw new InvalidArgumentException('Private key is required when certificate is string.');
            }

            $certificate = CertificateFactory::makeFromPkcs8($certificate, $privateKey, $passphrase);
        }

        $this->certificate = $certificate;

        return $this;
    }

    /**
     * Replaces the default HTTP Client adapter with a custom PSR-18 compatible client.
     *
     * @param BaseClientInterface $client
     * @return static
     */
    public function withHttpClient(BaseClientInterface $client): self
    {
        $this->httpClient = ClientFactory::make($client);

        return $this;
    }

    /**
     * Replaces the default Logger with a custom PSR-3 compatible logger.
     *
     * @param LoggerInterface $logger
     * @return static
     */
    public function withLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Sets the context identifier for the current session (NIP, NipVatUe, InternalId, PeppolId).
     *
     * @param NIP|NipVatUe|InternalId|PeppolId|string $identifier
     * @return static
     */
    public function withIdentifier(NIP | NipVatUe | InternalId | PeppolId | string $identifier): self
    {
        if (is_string($identifier)) {
            $identifier = NIP::from($identifier);
        }

        $this->identifier = $identifier;

        return $this;
    }

    public function withVerifyCertificateChain(bool $verifyCertificateChain): self
    {
        $this->verifyCertificateChain = $verifyCertificateChain;

        return $this;
    }

    public function withAsyncMaxConcurrency(int $asyncMaxConcurrency): self
    {
        $this->asyncMaxConcurrency = $asyncMaxConcurrency;

        return $this;
    }

    public function withValidateXml(bool $validateXml): self
    {
        $this->validateXml = $validateXml;

        return $this;
    }

    /**
     * Sets the output path and level for the default Monolog logger.
     *
     * @param LogPath|string|null $logPath
     * @param LogLevel::*|null $level
     * @return static
     */
    public function withLogPath(LogPath | string | null $logPath, ?string $level = LogLevel::DEBUG): self
    {
        if (is_string($logPath)) {
            $logPath = LogPath::from($logPath);
        }

        $this->logger = null;

        if ($level !== null) {
            $this->logger = LoggerFactory::make($logPath, $level);
        }

        return $this;
    }

    /**
     * Registers a custom exception handler.
     *
     * @param ExceptionHandlerInterface $exceptionHandler
     * @return static
     */
    public function withExceptionHandler(ExceptionHandlerInterface $exceptionHandler): self
    {
        $this->exceptionHandler = $exceptionHandler;

        return $this;
    }

    /**
     * Sets the cache implementation and the default time-to-live (TTL) for cache entries.
     *
     * @param CacheInterface $cache The cache implementation to use.
     * @param int $cacheTTL The default time-to-live in seconds for cache entries.
     * @return static
     */
    public function withCache(CacheInterface $cache, int $cacheTTL = 43200): self
    {
        $this->cache = $cache;
        $this->cacheTTL = $cacheTTL;

        return $this;
    }

    public function withValinorCache(Cache $cache): self
    {
        $this->valinorCache = $cache;

        return $this;
    }

    /**
     * Builds the KSeF Client.
     * 
     * If a certificate was provided without an access token, this method will
     * automatically call the KSeF auth challenge, XAdES signature generation, and token exchange endpoints.
     *
     * @return \Rcalicdan\KSEFClient\Resources\ClientResource
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception Thrown if authentication fails.
     * @throws \RuntimeException Thrown if cryptographic operations fail.
     */
    public function build(): ClientResource
    {
        $config = new Config(
            mode: $this->mode,
            baseUri: new BaseUri($this->apiUrl->value),
            latarniaBaseUri: new BaseUri($this->latarniaApiUrl->value),
            asyncMaxConcurrency: $this->asyncMaxConcurrency,
            validateXml: $this->validateXml,
            cacheTTL: $this->cacheTTL,
            accessToken: $this->accessToken,
            refreshToken: $this->refreshToken,
            encryptionKey: $this->encryptionKey,
        );

        $httpClient = new HttpClient(
            client: $this->httpClient,
            config: $config,
            logger: $this->logger
        );

        $this->exceptionHandler ??= new ExceptionHandler($this->logger);

        $client = new ClientResource(
            client: $httpClient,
            config: $config,
            exceptionHandler: $this->exceptionHandler,
            logger: $this->logger,
            cache: $this->cache,
            valinorCache: $this->valinorCache
        );

        if ($this->encryptionKey instanceof EncryptionKey) {
            $client = $client->withEncryptedKey($this->handleEncryptedKey($client));
        }

        if ($this->isAuthorisation()) {
            try {
                $authorisationResponse = $this->handleAuthorisationByCertificate($client);
            } catch (Throwable $throwable) {
                throw $this->exceptionHandler->handle($throwable);
            }

            /** @var object{referenceNumber: string, authenticationToken: object{token: string, validUntil: string}} $authorisationData */
            $authorisationData = $authorisationResponse->object();

            $client = $client->withAccessToken(AccessToken::from(
                $authorisationData->authenticationToken->token,
                new DateTimeImmutable($authorisationData->authenticationToken->validUntil)
            ));

            Utility::retry(function () use ($client, $authorisationData) {
                /** @var object{status: object{code: int, description: string, details?: array<int, string>}} $authorisationStatusResponse */
                $authorisationStatusResponse = $client->auth()->status(
                    new StatusRequest(ReferenceNumber::from($authorisationData->referenceNumber))
                )->object();

                if ($authorisationStatusResponse->status->code === 200) {
                    return $authorisationStatusResponse;
                }

                if ($authorisationStatusResponse->status->code >= 400) {
                    throw $this->exceptionHandler->handle(new StatusException(
                        message: $authorisationStatusResponse->status->description,
                        code: $authorisationStatusResponse->status->code,
                        context: $authorisationStatusResponse
                    ));
                }
            });

            /** @var object{refreshToken: object{token: string, validUntil: string}, accessToken: object{token: string, validUntil: string}} $authorisationTokenResponse */
            $authorisationTokenResponse = $client->auth()->token()->redeem()->object();

            $client = $client
                ->withAccessToken(AccessToken::from(
                    token: $authorisationTokenResponse->accessToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->accessToken->validUntil)
                ))
                ->withRefreshToken(RefreshToken::from(
                    token: $authorisationTokenResponse->refreshToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->refreshToken->validUntil)
                ));
        }

        return $client;
    }

    private function isAuthorisation(): bool
    {
        return ! $this->accessToken instanceof AccessToken && $this->certificate instanceof Certificate;
    }

    private function handleEncryptedKey(ClientResourceInterface $client): EncryptedKey
    {
        if ($this->encryptionKey instanceof EncryptionKey === false) {
            throw new RuntimeException('Encryption key is not set');
        }

        $securityResponse = $client->security()->publicKeyCertificates();

        $firstSymmetricKeyEncryptionCertificate = $securityResponse
            ->getFirstByPublicKeyCertificateUsage(PublicKeyCertificateUsage::SymmetricKeyEncryption);

        if ($firstSymmetricKeyEncryptionCertificate === null) {
            throw new RuntimeException('Symmetric key encryption certificate is not found');
        }

        $symmetricKeyEncryptionCertificate = base64_decode($firstSymmetricKeyEncryptionCertificate);

        $certificate = (new ConvertDerToPemHandler())->handle(new ConvertDerToPemAction(
            der: $symmetricKeyEncryptionCertificate,
            name: 'CERTIFICATE'
        ));

        $ksefPublicKey = KsefPublicKey::from($certificate);

        return EncryptedKeyFactory::make($this->encryptionKey, $ksefPublicKey);
    }

    private function handleAuthorisationByCertificate(ClientResourceInterface $client): ResponseInterface
    {
        if (! $this->certificate instanceof Certificate) {
            throw new RuntimeException('Certificate is not set');
        }

        /** @var object{challenge: string, timestamp: string} $challengeResponse */
        $challengeResponse = $client->auth()->challenge()->object();

        return $client->auth()->xadesSignature(
            new XadesSignatureRequest(
                certificate: $this->certificate,
                xadesSignature: new XadesSignature(
                    challenge: Challenge::from($challengeResponse->challenge),
                    contextIdentifierGroup: ContextIdentifierGroup::fromIdentifier($this->identifier),
                    subjectIdentifierType: SubjectIdentifierType::CertificateSubject
                ),
                verifyCertificateChain: $this->verifyCertificateChain
            )
        );
    }
}
