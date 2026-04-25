<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Validator\Rules\Utility;

use Rcalicdan\KSEFClient\Validator\Rules\AbstractRule;

final class RequiredRule extends AbstractRule
{
    public function handle(mixed $value, ?string $attribute = null): void
    {
        if (empty($value)) {
            $this->throwRuleValidationException('The value is required.', $attribute);
        }
    }
}
