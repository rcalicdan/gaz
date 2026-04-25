<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Status;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final class StatusRequest extends AbstractRequest
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
    ) {
    }
}
