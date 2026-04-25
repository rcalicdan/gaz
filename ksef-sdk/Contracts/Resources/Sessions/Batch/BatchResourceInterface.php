<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Batch;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Requests\Sessions\Batch\OpenAndSend\OpenAndSendResponseInterface;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\Close\CloseRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendXmlRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\OpenAndSendZipRequest;

/**
 * Batch (Asynchronous) Session Management.
 * Handles large-scale invoice processing by automatically zipping, encrypting, and splitting payloads.
 *
 * @api
 */
interface BatchResourceInterface
{
    /**
     * Opens a batch session, processes the invoices (zips, encrypts, chunks), and uploads the parts.
     * 
     * You can provide:
     * - OpenAndSendRequest: Array of hydrated `Faktura` DTOs.
     * - OpenAndSendXmlRequest: Array of raw XML strings.
     * - OpenAndSendZipRequest: A pre-zipped archive string.
     *
     * @param OpenAndSendRequest|OpenAndSendXmlRequest|OpenAndSendZipRequest|array<string, mixed> $request
     * @return OpenAndSendResponseInterface Contains the `referenceNumber` and async part upload responses.
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     * @throws \RuntimeException Thrown if encryption or zipping fails.
     */
    public function openAndSend(OpenAndSendRequest | OpenAndSendXmlRequest | OpenAndSendZipRequest | array $request): OpenAndSendResponseInterface;

    /**
     * Closes the active batch session, signaling KSeF to begin processing the uploaded packages.
     *
     * @param CloseRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function close(CloseRequest | array $request): ResponseInterface;
}