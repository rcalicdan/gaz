<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\DecryptDocument;

use Rcalicdan\KSEFClient\Actions\AbstractAction;
use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;

final class DecryptDocumentAction extends AbstractAction
{
    public function __construct(
        public readonly EncryptionKey $encryptionKey,
        public readonly string $document,
    ) {
    }
}
