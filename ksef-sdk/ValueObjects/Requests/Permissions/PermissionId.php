<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\String\MaxRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class PermissionId extends AbstractValueObject implements Stringable, FromInterface
{
    public readonly string $value;

    public function __construct(string $value)
    {
        Validator::validate($value, [
            new MaxRule(36)
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
