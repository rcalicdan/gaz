<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\QRCode;

final class QRCodes extends AbstractDTO
{
    public function __construct(
        public readonly QRCode $code1,
        public readonly ?QRCode $code2 = null,
    ) {
    }
}
