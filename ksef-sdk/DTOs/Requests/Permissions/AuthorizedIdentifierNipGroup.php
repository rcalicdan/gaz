<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

final class AuthorizedIdentifierNipGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly NIP $nip,
    ) {
    }

    public function getIdentifier(): NIP
    {
        return $this->nip;
    }
}
