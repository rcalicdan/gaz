<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Authorizations\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\Authorizations\SubjectDetails;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierPeppolIdGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\AuthorizationPermissionType;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly SubjectIdentifierNipGroup | SubjectIdentifierPeppolIdGroup $subjectIdentifierGroup,
        public readonly AuthorizationPermissionType $permission,
        public readonly Description $description,
        public readonly SubjectDetails $subjectDetails,
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
