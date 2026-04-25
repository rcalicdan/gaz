<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Invoices\KsefUpo;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\KsefNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final class KsefUpoRequest extends AbstractRequest
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
        public readonly KsefNumber $ksefNumber
    ) {
    }
}
