<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;

final class SubjectIdentifierPeppolIdGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly PeppolId $peppolId,
    ) {
    }

    public function getIdentifier(): PeppolId
    {
        return $this->peppolId;
    }
}
