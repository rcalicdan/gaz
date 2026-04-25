<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\Date\AfterRule;
use Rcalicdan\KSEFClient\Validator\Rules\Date\BeforeRule;
use Rcalicdan\KSEFClient\Validator\Rules\Date\TimezoneRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class DataGodzRozpTransportu extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value, new DateTimeZone('UTC'));
        }

        Validator::validate($value, [
            new TimezoneRule(['UTC', 'Z']),
            new BeforeRule(new DateTimeImmutable('2050-01-01T23:59:59Z')),
            new AfterRule(new DateTimeImmutable('2021-10-01T00:00:00Z')),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d\TH:i:s\Z');
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
