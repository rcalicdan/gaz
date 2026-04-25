<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\File\ExtensionsRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class LogXmlFilename extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new ExtensionsRule(['xml']),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function withoutSlashAtStart(): self
    {
        return str_starts_with($this->value, '/') ? new self(ltrim($this->value, '/')) : $this;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
