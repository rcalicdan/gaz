<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\RegexRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class NrVatUE extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new RegexRule('/^(\d|[A-Z]|\+|\*){1,12}$/'),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
