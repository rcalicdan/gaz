<?php

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Attachment;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;
use Rcalicdan\KSEFClient\Requests\Testdata\Attachment\Approve\ApproveRequest;
use Rcalicdan\KSEFClient\Requests\Testdata\Attachment\Revoke\RevokeRequest;

interface AttachmentResourceInterface
{
    /**
     * @param ApproveRequest|array<string, mixed> $request
     */
    public function approve(ApproveRequest | array $request): ResponseInterface;

    /**
     * @param RevokeRequest|array<string, mixed> $request
     */
    public function revoke(RevokeRequest | array $request): ResponseInterface;
}
