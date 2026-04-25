<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Auth;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Auth\Sessions\SessionsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Auth\Token\TokenResourceInterface;
use Rcalicdan\KSEFClient\Requests\Auth\Status\StatusRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureXmlRequest;

/**
 * Manual Authentication and Authorization Resource.
 *
 * @api
 */
interface AuthResourceInterface
{
    /**
     * Initializes the authorization challenge.
     * Returns a cryptographic challenge required for generating a XAdES signature.
     *
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function challenge(): ResponseInterface;

    /**
     * Submits a XAdES-signed authorization request to obtain an initial session token.
     * The SDK automatically signs the request if a `Certificate` is provided in `XadesSignatureRequest`.
     *
     * @param XadesSignatureRequest|XadesSignatureXmlRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function xadesSignature(XadesSignatureRequest | XadesSignatureXmlRequest | array $request): ResponseInterface;

    /**
     * Checks the authorization status of a specific session reference number.
     *
     * @param StatusRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function status(StatusRequest | array $request): ResponseInterface;

    /**
     * Access token lifecycle operations (Redeem, Refresh).
     */
    public function token(): TokenResourceInterface;

    /**
     * Access authentication session list and revocation operations.
     */
    public function sessions(): SessionsResourceInterface;
}