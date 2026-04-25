<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertPemToDer;

use Rcalicdan\KSEFClient\Actions\AbstractAction;

final class ConvertPemToDerAction extends AbstractAction
{
    public function __construct(
        public readonly string $pem
    ) {
    }
}
