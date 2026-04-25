<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Invoices\Download;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\KsefNumber;

final class DownloadRequest extends AbstractRequest
{
    public function __construct(
        public readonly KsefNumber $ksefNumber
    ) {
    }
}
