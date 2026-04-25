<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Certificates;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\Number\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Number\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class PageSize extends AbstractValueObject implements FromInterface, ValueAwareInterface
{
    public readonly int $value;

    public function __construct(int $value)
    {
        Validator::validate($value, [
            new MinRule(10),
            new MaxRule(50),
        ]);

        $this->value = $value;
    }

    public static function from(int $value): self
    {
        return new self($value);
    }
}
