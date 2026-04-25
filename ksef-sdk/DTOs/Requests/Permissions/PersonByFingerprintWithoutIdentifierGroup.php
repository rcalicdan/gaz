<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\SubjectDetailsType;

final class PersonByFingerprintWithoutIdentifierGroup extends AbstractDTO implements BodyInterface
{
    use HasToBody;

    public readonly SubjectDetailsType $subjectDetailsType;

    public function __construct(
        public readonly PersonByFpNoId $personByFpNoId,
    ) {
        $this->subjectDetailsType = SubjectDetailsType::PersonByFingerprintWithoutIdentifier;
    }
}
