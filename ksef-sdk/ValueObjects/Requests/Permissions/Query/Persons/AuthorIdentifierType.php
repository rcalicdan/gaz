<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\Persons;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum AuthorIdentifierType: string implements EnumInterface
{
    case System = 'System';

    public function getType(): string
    {
        return $this->value;
    }
}
