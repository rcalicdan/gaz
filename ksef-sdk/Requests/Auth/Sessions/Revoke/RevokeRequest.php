<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\Sessions\Revoke;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final class RevokeRequest extends AbstractRequest
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
    ) {
    }
}
