<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Subject;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;

final class Subunit extends AbstractDTO
{
    public function __construct(
        public readonly NIP $subjectNip,
        public readonly Description $description,
    ) {
    }
}
