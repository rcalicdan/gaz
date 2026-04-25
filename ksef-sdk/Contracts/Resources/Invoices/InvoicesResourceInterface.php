<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Invoices;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Invoices\Exports\ExportsResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Invoices\Query\QueryResourceInterface;
use Rcalicdan\KSEFClient\Requests\Invoices\Download\DownloadRequest;

/**
 * Operations on Invoices outside of an active session (Query, Download, Export).
 * 
 * @api
 */
interface InvoicesResourceInterface
{
    /**
     * Downloads an exact XML representation of a specific invoice by its KSeF number.
     *
     * @param DownloadRequest|array<string, mixed> $request
     * @return ResponseInterface Call `->body()` on the response to get the raw XML.
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function download(DownloadRequest | array $request): ResponseInterface;

    /**
     * Access invoice querying by metadata.
     */
    public function query(): QueryResourceInterface;

    /**
     * Access asynchronous invoice package exports.
     */
    public function exports(): ExportsResourceInterface;
}