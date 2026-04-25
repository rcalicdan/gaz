<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR;

use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\String\MinRule;
use Rcalicdan\KSEFClient\Validator\Rules\String\RegexRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class LinkDoPlatnosci extends AbstractValueObject implements ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MinRule(1),
            new MaxRule(512),
            new RegexRule('~^(https?)://([a-zA-Z0-9][a-zA-Z0-9-]*\.)+[a-zA-Z]{2,}(:\d{1,5})?(/[^\s?#]*)?\?([^#\s]*&)?IPKSeF=\d{3}[a-zA-Z0-9]{10}(&[^#\s]*)?(#.*)?$~')
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
