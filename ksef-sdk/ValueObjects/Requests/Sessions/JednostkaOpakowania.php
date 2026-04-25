<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\String\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class JednostkaOpakowania extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(1),
            new MaxRule(256),
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
