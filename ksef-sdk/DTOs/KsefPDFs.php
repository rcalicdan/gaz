<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs;

use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class KsefPDFs extends AbstractDTO
{
    public function __construct(
        public readonly ?string $invoice = null,
        public readonly ?string $upo = null,
        public readonly ?string $confirmation = null
    ) {
    }
}
