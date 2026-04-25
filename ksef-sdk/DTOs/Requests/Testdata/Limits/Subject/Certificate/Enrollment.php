<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Subject\Certificate;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Number\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Enrollment extends AbstractDTO
{
    public function __construct(
        public readonly int $maxEnrollments,
    ) {
        Validator::validate($this->toArray(), [
            'maxEnrollments' => [new MinRule(0)],
        ]);
    }
}
