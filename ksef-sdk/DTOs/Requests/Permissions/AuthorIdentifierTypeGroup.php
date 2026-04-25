<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\Persons\AuthorIdentifierType;

final class AuthorIdentifierTypeGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly AuthorIdentifierType $type,
    ) {
    }

    public function getIdentifier(): AuthorIdentifierType
    {
        return $this->type;
    }
}
