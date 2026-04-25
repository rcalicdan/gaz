<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Invoices\Query;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Invoices\Query\Metadata\MetadataRequest;

/**
 * Query operations for searching invoices in KSeF.
 *
 * @api
 */
interface QueryResourceInterface
{
    /**
     * Queries invoice metadata based on specific filters (e.g., Date ranges, Subject Type).
     * This is useful for fetching lists of invoices received from contractors.
     *
     * @param MetadataRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function metadata(MetadataRequest | array $request): ResponseInterface;
}