<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Subunits\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierInternalIdGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithoutIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierFingerprintGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierPeselGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Subunits\SubunitName;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody {
        HasToBody::toBody as baseToBody;
    }

    public function __construct(
        public readonly SubjectIdentifierNipGroup | SubjectIdentifierPeselGroup | SubjectIdentifierFingerprintGroup $subjectIdentifierGroup,
        public readonly ContextIdentifierNipGroup | ContextIdentifierInternalIdGroup $contextIdentifierGroup,
        public readonly Description $description,
        public readonly SubunitName $subunitName,
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
            'contextIdentifier' => [
                'type' => $this->contextIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->contextIdentifierGroup->getIdentifier(),
            ],
        ];
    }
}
