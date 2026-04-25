<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertPemToDer;

use Rcalicdan\KSEFClient\Actions\AbstractHandler;

final class ConvertPemToDerHandler extends AbstractHandler
{
    public function handle(ConvertPemToDerAction $action): string
    {
        /** @var string $der */
        $der = preg_replace('/-+BEGIN [^-]+-+|-+END [^-]+-+|\s+/', '', $action->pem);

        return base64_decode($der);
    }
}
