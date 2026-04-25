<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects;

use Rcalicdan\KSEFClient\Contracts\FromInterface;
use Rcalicdan\KSEFClient\Contracts\ValueAwareInterface;
use Rcalicdan\KSEFClient\Support\AbstractValueObject;
use Rcalicdan\KSEFClient\Validator\Rules\File\ExistsRule;
use Rcalicdan\KSEFClient\Validator\Rules\File\ExtensionsRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Stringable;

final class KsefFeInvoiceConverterPath extends AbstractValueObject implements FromInterface, ValueAwareInterface, Stringable
{
    public readonly string $value;

    public function __construct(
        string $value,
    ) {
        Validator::validate($value, [
            new ExistsRule(),
            new ExtensionsRule(['js', 'exe']),
        ]);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $path): self
    {
        return new self($path);
    }
}
