<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Permissions\Attachments;

use Rcalicdan\KSEFClient\Contracts\HttpClient\ResponseInterface;

interface AttachmentsResourceInterface
{
    public function status(): ResponseInterface;
}
