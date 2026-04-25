<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\Date\AfterRule;
use Rcalicdan\KSEFClient\Validator\Rules\Date\BeforeRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class P_6_Od extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value);
        }

        Validator::validate($value, [
            new BeforeRule(new DateTimeImmutable('2050-01-01')),
            new AfterRule(new DateTimeImmutable('2006-01-01')),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d');
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
