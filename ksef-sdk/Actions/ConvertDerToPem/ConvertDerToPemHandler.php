<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertDerToPem;

use Rcalicdan\KSEFClient\Actions\AbstractHandler;

final class ConvertDerToPemHandler extends AbstractHandler
{
    public function handle(ConvertDerToPemAction $action): string
    {
        return "-----BEGIN {$action->name}-----\n"
            . chunk_split(base64_encode($action->der), 64, "\n")
            . "-----END {$action->name}-----\n";
        ;
    }
}
