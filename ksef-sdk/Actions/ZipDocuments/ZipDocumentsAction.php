<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ZipDocuments;

use Rcalicdan\KSEFClient\Actions\AbstractAction;

final class ZipDocumentsAction extends AbstractAction
{
    /**
     * @param array<int, string> $documents
     */
    public function __construct(
        public readonly array $documents
    ) {
    }
}
