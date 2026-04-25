<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Validator\Rules\File;

use Rcalicdan\KSEFClient\Validator\Rules\AbstractRule;

final class ExtensionsRule extends AbstractRule
{
    /**
     * @param array<int, string> $extensions
     */
    public function __construct(
        private readonly array $extensions
    ) {
    }

    public function handle(string $value, ?string $attribute = null): void
    {
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if ( ! in_array($extension, $this->extensions)) {
            $this->throwRuleValidationException(
                'File has invalid extension. Available extensions: %s.',
                $attribute,
                implode(', ', $this->extensions)
            );
        }
    }
}
