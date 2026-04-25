<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\EuEntities\Administration\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipVatUeGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\EntityByFingerprintGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\EuEntities\Administration\EuEntityDetails;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\PersonByFingerprintWithoutIdentifierGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\SubjectIdentifierFingerprintGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\EuEntities\Administration\EuEntityName;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly SubjectIdentifierFingerprintGroup $subjectIdentifierGroup,
        public readonly ContextIdentifierNipVatUeGroup $contextIdentifierGroup,
        public readonly Description $description,
        public readonly EuEntityName $euEntityName,
        public readonly PersonByFingerprintWithIdentifierGroup | PersonByFingerprintWithoutIdentifierGroup | EntityByFingerprintGroup $subjectDetails,
        public readonly EuEntityDetails $euEntityDetails
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray();

        return [
            ...$data,
            'euEntityName' => (string) $this->euEntityName,
            'euEntityDetails' => $this->euEntityDetails->toBody(),
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
