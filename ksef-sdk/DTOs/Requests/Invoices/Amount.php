<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Invoices;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\AmountFrom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\AmountTo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\AmountType;

final class Amount extends AbstractDTO
{
    public function __construct(
        public readonly AmountType $type,
        public readonly Optional | AmountFrom $from = new Optional(),
        public readonly Optional | AmountTo $to = new Optional(),
    ) {
    }
}
