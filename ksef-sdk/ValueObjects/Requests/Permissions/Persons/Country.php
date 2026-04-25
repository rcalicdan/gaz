<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Persons;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\CountryRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class Country extends AbstractValueObject implements Stringable, FromInterface
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new CountryRule(),
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
