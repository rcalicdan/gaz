<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Attachment\Revoke;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Attachment\ExpectedEndDate;

final class RevokeRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly NIP $nip,
        public readonly Optional | ExpectedEndDate $expectedEndDate = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray();
    }
}
