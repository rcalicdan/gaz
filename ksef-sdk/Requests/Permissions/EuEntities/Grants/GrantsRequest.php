<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\EuEntities\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\EntityByFingerprintGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithoutIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierFingerprintGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\EuEntities\EuEntityPermissionType;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param array<int, EuEntityPermissionType> $permissions
     */
    public function __construct(
        public readonly SubjectIdentifierFingerprintGroup $subjectIdentifierGroup,
        public readonly array $permissions,
        public readonly Description $description,
        public readonly PersonByFingerprintWithIdentifierGroup | PersonByFingerprintWithoutIdentifierGroup | EntityByFingerprintGroup $subjectDetails,
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray();

        return [
            ...$data,
            'subjectIdentifier' => [
                'type' => $this->subjectIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->subjectIdentifierGroup->getIdentifier(),
            ],
        ];
    }
}
