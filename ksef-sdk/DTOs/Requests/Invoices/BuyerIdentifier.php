<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Invoices;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\BuyerIdentifierType;

final class BuyerIdentifier extends AbstractDTO
{
    public function __construct(
        public readonly BuyerIdentifierType $type,
        public readonly Optional | string $value = new Optional(),
    ) {
    }
}
