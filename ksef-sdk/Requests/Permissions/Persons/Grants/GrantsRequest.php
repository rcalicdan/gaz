<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Persons\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithoutIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierFingerprintGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierPeselGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Persons\PersonPermissionType;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody {
        HasToBody::toBody as baseToBody;
    }

    /**
     * @param array<int, PersonPermissionType> $permissions
     */
    public function __construct(
        public readonly SubjectIdentifierNipGroup | SubjectIdentifierPeselGroup | SubjectIdentifierFingerprintGroup $subjectIdentifierGroup,
        public readonly array $permissions,
        public readonly Description $description,
        public readonly PersonByIdentifierGroup | PersonByFingerprintWithIdentifierGroup | PersonByFingerprintWithoutIdentifierGroup $subjectDetails,
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->baseToBody();

        return [
            ...$data,
            'subjectIdentifier' => [
                'type' => $this->subjectIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->subjectIdentifierGroup->getIdentifier(),
            ],
        ];
    }
}
