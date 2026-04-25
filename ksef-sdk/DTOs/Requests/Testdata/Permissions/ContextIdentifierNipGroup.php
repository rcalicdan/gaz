<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

final class ContextIdentifierNipGroup extends AbstractDTO implements IdentifierInterface
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
