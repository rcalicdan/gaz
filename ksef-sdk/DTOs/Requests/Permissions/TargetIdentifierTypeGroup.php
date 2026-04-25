<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\TargetIdentifierType;

final class TargetIdentifierTypeGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly TargetIdentifierType $type,
    ) {
    }

    public function getIdentifier(): TargetIdentifierType
    {
        return $this->type;
    }
}
