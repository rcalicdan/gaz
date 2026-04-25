<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Subject\Create;

use DateTime;
use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Subject\Subunit;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Subject\SubjectType;

final class CreateRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @param Optional|array<int, Subunit> $subunits
     */
    public function __construct(
        public readonly NIP $subjectNip,
        public readonly SubjectType $subjectType,
        public readonly Description $description,
        public readonly Optional | array $subunits = new Optional(),
        public readonly Optional | DateTime | null $createdDate = new Optional(),
    ) {
    }
}
