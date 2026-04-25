<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Certificates\Query;

use DateTime;
use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\CertificateSerialNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\CertificateName;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\CertificateStatus;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\CertificateType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates\PageSize;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;

final class QueryRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    public function __construct(
        public readonly Optional | CertificateName | null $name = new Optional(),
        public readonly Optional | CertificateType | null $type = new Optional(),
        public readonly Optional | CertificateStatus | null $status = new Optional(),
        public readonly Optional | CertificateSerialNumber | null $certificateSerialNumber = new Optional(),
        public readonly Optional | DateTime | null $expiresAfter = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['name', 'type', 'status', 'certificateSerialNumber', 'expiresAfter']);
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize', 'pageOffset']);
    }
}
