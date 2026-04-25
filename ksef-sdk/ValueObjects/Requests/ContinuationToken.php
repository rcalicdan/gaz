<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class ContinuationToken extends AbstractValueObject implements ValueAwareInterface, Stringable, FromInterface
{
    public function __construct(public readonly string $value)
    {
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
