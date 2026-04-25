<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Latarnia;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;

/**
 * Access to the KSeF Latarnia API (System Status and Maintenance Messages).
 * Extremely useful for checking if KSeF is currently experiencing an outage (AWARIA)
 * or has scheduled maintenance, before attempting to send invoices.
 *
 * @api
 */
interface LatarniaResourceInterface
{
    /**
     * Retrieves the current operational status of the KSeF environment.
     *
     * @return ResponseInterface 
     * 
     * @example
     * /** @var object{status: string, messages: array<int, object>} $data *\/
     * $data = $client->latarnia()->status()->object();
     * 
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function status(): ResponseInterface;

    /**
     * Retrieves a list of active and upcoming maintenance/failure messages.
     *
     * @return ResponseInterface
     * 
     * @example
     * /** @var array<int, object{id: string, category: string, type: string, title: string, text: string, start: string, end?: string}> $messages *\/
     * $messages = $client->latarnia()->messages()->object();
     * 
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function messages(): ResponseInterface;
}