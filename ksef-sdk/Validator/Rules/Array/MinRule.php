<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Validator\Rules\Array;

use Rcalicdan\KSEFClient\Validator\Rules\AbstractRule;

final class MinRule extends AbstractRule
{
    public function __construct(
        private readonly int $min
    ) {
    }

    /**
     * @param array<int, mixed> $value
     */
    public function handle(array $value, ?string $attribute = null): void
    {
        if (count($value) < $this->min) {
            $this->throwRuleValidationException(
                'Value must have at least %d elements.',
                $attribute,
                $this->min
            );
        }
    }
}
