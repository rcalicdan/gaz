<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Rcalicdan\KSEFClient\Contracts\OriginalInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\Date\TimezoneRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class DateRangeFrom extends AbstractValueObject implements ValueAwareInterface, Stringable, OriginalInterface
{
    public readonly DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value, new DateTimeZone('UTC'));
        }

        Validator::validate($value, [
            new TimezoneRule(['UTC', 'Z']),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d\TH:i:s.uP');
    }

    public function toOriginal(): string
    {
        return (string) $this;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
