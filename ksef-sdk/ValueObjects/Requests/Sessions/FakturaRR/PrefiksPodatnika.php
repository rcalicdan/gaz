<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\CountryUERule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class PrefiksPodatnika extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new CountryUERule(),
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
