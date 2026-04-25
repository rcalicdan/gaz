<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Revoke;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\CertificateSerialNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\RevocationReason;

final class RevokeRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly CertificateSerialNumber $certificateSerialNumber,
        public readonly Optional | RevocationReason | null $revocationReason = new Optional()
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['revocationReason']);
    }
}
