<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class Fingerprint extends AbstractValueObject implements FromInterface, Stringable, ValueAwareInterface
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

    public function getType(): string
    {
        return 'Fingerprint';
    }
}
