<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NipVatUe;

final class ContextIdentifierNipVatUeGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly NipVatUe $nipVatUe,
    ) {
    }

    public function getIdentifier(): NipVatUe
    {
        return $this->nipVatUe;
    }
}
