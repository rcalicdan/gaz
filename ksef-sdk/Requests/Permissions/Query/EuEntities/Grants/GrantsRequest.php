<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\EuEntities\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Fingerprint;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageSize;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\EuEntities\EuEntityPermissionType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\EuEntities\VatUe;

final class GrantsRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    /**
     * @param Optional|array<int, EuEntityPermissionType> $permissionTypes
     */
    public function __construct(
        public readonly Optional | VatUe $vatUeIdentifier = new Optional(),
        public readonly Optional | Fingerprint $authorizedFingerprintIdentifier = new Optional(),
        public readonly Optional | array $permissionTypes = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: [
            'vatUeIdentifier',
            'authorizedFingerprintIdentifier',
            'permissionTypes',
        ]);
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: [
            'pageOffset',
            'pageSize',
        ]);
    }
}
