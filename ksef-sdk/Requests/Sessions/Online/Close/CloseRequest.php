<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Close;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final class CloseRequest extends AbstractRequest
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
    ) {
    }
}
