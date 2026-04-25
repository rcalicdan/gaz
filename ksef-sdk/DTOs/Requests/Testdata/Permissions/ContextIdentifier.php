<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class ContextIdentifier extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public readonly ContextIdentifierNipGroup $identifierGroup,
    ) {
    }

    public function toBody(): array
    {
        return [
            'type' => $this->identifierGroup->getIdentifier()->getType(),
            'value' => (string) $this->identifierGroup->getIdentifier(),
        ];
    }
}
