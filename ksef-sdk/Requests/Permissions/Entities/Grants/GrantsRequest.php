<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Entities\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\Entities\EntityPermission;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\Entities\SubjectDetails;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierNipGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param array<int, EntityPermission> $permissions
     */
    public function __construct(
        public readonly SubjectIdentifierNipGroup $subjectIdentifierGroup,
        public readonly array $permissions,
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
