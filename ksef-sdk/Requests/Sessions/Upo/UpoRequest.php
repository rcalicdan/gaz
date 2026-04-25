<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Upo;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final class UpoRequest extends AbstractRequest
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
        public readonly ReferenceNumber $upoReferenceNumber
    ) {
    }
}
