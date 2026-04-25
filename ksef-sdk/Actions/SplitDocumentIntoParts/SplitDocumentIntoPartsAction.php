<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\SplitDocumentIntoParts;

use Rcalicdan\KSEFClient\Actions\AbstractAction;

final class SplitDocumentIntoPartsAction extends AbstractAction
{
    public function __construct(
        public readonly string $document,
        public readonly int $partSize
    ) {
    }
}
