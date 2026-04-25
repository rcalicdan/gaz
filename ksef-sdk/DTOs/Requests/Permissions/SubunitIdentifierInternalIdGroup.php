<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\InternalId;

final class SubunitIdentifierInternalIdGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly InternalId $internalId,
    ) {
    }

    public function getIdentifier(): InternalId
    {
        return $this->internalId;
    }
}
