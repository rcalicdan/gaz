<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Online;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Send\SendRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Send\SendXmlRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;

/**
 * Interactive (Online) Session Management.
 * 
 * @api
 */
interface OnlineResourceInterface
{
    /**
     * Opens a new interactive session (InitSessionTokenRequest).
     *
     * @param OpenRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function open(OpenRequest | array $request): ResponseInterface;

    /**
     * Closes the active interactive session (TerminateSessionRequest).
     *
     * @param CloseRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function close(CloseRequest | array $request): ResponseInterface;

    /**
     * Sends an invoice to the KSeF system synchronously (SendInvoiceRequest).
     * Automatically encrypts the invoice payload using the AES key.
     *
     * @param SendRequest|SendXmlRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     * @throws \Rcalicdan\KSEFClient\Exceptions\RuleValidationException Thrown if XML schema validation fails.
     */
    public function send(SendRequest | SendXmlRequest | array $request): ResponseInterface;
}