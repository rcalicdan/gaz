<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertEcdsaDerToRaw;

use Rcalicdan\KSEFClient\Actions\AbstractAction;

final class ConvertEcdsaDerToRawAction extends AbstractAction
{
    public function __construct(
        public readonly string $der,
        public readonly int $keySize = 32,
    ) {
    }
}
