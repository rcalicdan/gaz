<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Validator\Rules\String;

use Rcalicdan\KSEFClient\Validator\Rules\AbstractRule;

final class EmailRule extends AbstractRule
{
    public function handle(string $value, ?string $attribute = null): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->throwRuleValidationException('Invalid email format.', $attribute);
        }
    }
}
