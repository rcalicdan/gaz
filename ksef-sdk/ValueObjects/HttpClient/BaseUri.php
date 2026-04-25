<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\HttpClient;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\ValueObjects\Url;
use Stringable;

final class BaseUri extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly Url $value;

    public function __construct(Url | string $value)
    {
        if ($value instanceof Url === false) {
            $value = Url::from($value);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value->value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
