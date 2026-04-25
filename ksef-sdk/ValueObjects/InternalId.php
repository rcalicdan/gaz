<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\RegexRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class InternalId extends AbstractValueObject implements FromInterface, Stringable, ValueAwareInterface
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            // @see https://ksef-test.mf.gov.pl/docs/v2/schemas/authv2.xsd
            new RegexRule('/^[1-9]((\d[1-9])|([1-9]\d))\d{7}-\d{5}$/'),
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

    public function getType(): string
    {
        return 'InternalId';
    }
}
