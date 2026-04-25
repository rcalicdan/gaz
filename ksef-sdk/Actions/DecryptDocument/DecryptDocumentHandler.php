<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\DecryptDocument;

use Rcalicdan\KSEFClient\Actions\AbstractHandler;
use RuntimeException;

final class DecryptDocumentHandler extends AbstractHandler
{
    public function handle(DecryptDocumentAction $action): string
    {
        $decryptedDocument = openssl_decrypt(
            $action->document,
            'AES-256-CBC',
            $action->encryptionKey->key,
            OPENSSL_RAW_DATA,
            $action->encryptionKey->iv
        );

        if ($decryptedDocument === false) {
            throw new RuntimeException('Unable to decrypt document');
        }

        return $decryptedDocument;
    }
}
