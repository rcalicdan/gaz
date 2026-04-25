<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertDerToPem;

use Rcalicdan\KSEFClient\Actions\AbstractAction;

final class ConvertDerToPemAction extends AbstractAction
{
    public function __construct(
        public readonly string $der,
        public readonly string $name
    ) {
    }
}
