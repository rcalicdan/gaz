<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use Rcalicdan\KSEFClient\Contracts\OriginalInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class DateClosedFrom extends AbstractValueObject implements ValueAwareInterface, Stringable, OriginalInterface
{
    public readonly DateTimeInterface $value;

    public function __construct(DateTimeInterface | string $value)
    {
        if ($value instanceof DateTimeInterface === false) {
            $value = new DateTimeImmutable($value);
        }

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
