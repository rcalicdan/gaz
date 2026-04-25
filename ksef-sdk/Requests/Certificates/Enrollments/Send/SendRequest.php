<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Enrollments\Send;

use DateTime;
use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\CertificateName;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\CertificateType;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\Support\Optional;

final class SendRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly CertificateName $certificateName,
        public readonly CertificateType $certificateType,
        public readonly string $csr,
        public readonly Optional | DateTime | null $validFrom = new Optional()
    ) {
    }
}
