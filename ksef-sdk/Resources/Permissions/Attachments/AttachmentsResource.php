<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Resources\Permissions\Attachments;

use Rcalicdan\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Attachments\AttachmentsResourceInterface;
use Rcalicdan\KSEFClient\Requests\Permissions\Attachments\Status\StatusHandler;
use Rcalicdan\KSEFClient\Resources\AbstractResource;
use Throwable;

final class AttachmentsResource extends AbstractResource implements AttachmentsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ExceptionHandlerInterface $exceptionHandler
    ) {
    }

    public function status(): ResponseInterface
    {
        try {
            return (new StatusHandler($this->client))->handle();
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}
