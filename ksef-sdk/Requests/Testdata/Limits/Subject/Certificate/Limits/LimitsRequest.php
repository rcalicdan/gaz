<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Limits\Subject\Certificate\Limits;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Subject\Certificate\Certificate;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Subject\Certificate\Enrollment;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Limits\Subject\Certificate\SubjectIdentifierType;

final class LimitsRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly SubjectIdentifierType $subjectIdentifierType,
        public readonly Enrollment $enrollment,
        public readonly Certificate $certificate,
    ) {
    }
}
