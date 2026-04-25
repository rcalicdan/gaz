<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Limits;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;

/**
 * Information about KSeF system limits for the current context and environment.
 *
 * @api
 */
interface LimitsResourceInterface
{
    /**
     * Retrieves current limits for sessions (e.g., max invoice size in MB, max invoices per batch).
     *
     * @return ResponseInterface
     * 
     * @example
     * /** @var object{onlineSession: object{maxInvoiceSizeInMB: int, maxInvoices: int}, batchSession: object{...}} $data *\/
     * $data = $client->limits()->context()->object();
     * 
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function context(): ResponseInterface;

    /**
     * Retrieves subject limits (e.g., maximum number of active certificates allowed per subject).
     *
     * @return ResponseInterface
     * 
     * @example
     * /** @var object{certificate: object{maxCertificates: int}} $data *\/
     * $data = $client->limits()->subject()->object();
     * 
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function subject(): ResponseInterface;
}