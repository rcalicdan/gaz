<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Sessions;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Batch\BatchResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Invoices\InvoicesResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Sessions\Online\OnlineResourceInterface;
use Rcalicdan\KSEFClient\Requests\Sessions\List\ListRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Status\StatusRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Upo\UpoRequest;

/**
 * Main hub for managing KSeF sessions (Online, Batch, and Global Session Queries).
 *
 * @api
 */
interface SessionsResourceInterface
{
    /**
     * Access Interactive (Online) session operations (Open, Send, Close).
     * Used for real-time, synchronous invoice submission.
     */
    public function online(): OnlineResourceInterface;

    /**
     * Access Batch session operations (OpenAndSend, Close).
     * Used for asynchronous, high-volume invoice package submission.
     */
    public function batch(): BatchResourceInterface;

    /**
     * Checks the status of any active or historical session.
     *
     * @param StatusRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function status(StatusRequest | array $request): ResponseInterface;

    /**
     * Retrieves a paginated list of historical sessions based on provided filters.
     *
     * @param ListRequest|array<string, mixed> $request
     * @return ResponseInterface
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function list(ListRequest | array $request): ResponseInterface;

    /**
     * Access invoice-specific operations within the context of a session.
     * (e.g., listing invoices sent in a session, checking failed invoices).
     */
    public function invoices(): InvoicesResourceInterface;

    /**
     * Retrieves the UPO (Urzędowe Poświadczenie Odbioru) for a specific session.
     *
     * @param UpoRequest|array<string, mixed> $request
     * @return ResponseInterface Call `->body()` to retrieve the raw XML of the UPO.
     * @throws \Rcalicdan\KSEFClient\Exceptions\HttpClient\Exception
     */
    public function upo(UpoRequest | array $request): ResponseInterface;
}