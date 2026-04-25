<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\SignDocument;

use Rcalicdan\KSEFClient\Actions\AbstractAction;
use Rcalicdan\KSEFClient\ValueObjects\Certificate;
use Rcalicdan\KSEFClient\ValueObjects\PrivateKeyType;

final class SignDocumentAction extends AbstractAction
{
    public function __construct(
        public readonly Certificate $certificate,
        public readonly string $document,
    ) {
    }

    public function getSignatureMethodNamespace(): string
    {
        return match ($this->certificate->getPrivateKeyType()) {
            PrivateKeyType::RSA => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
            PrivateKeyType::EC => 'http://www.w3.org/2001/04/xmldsig-more#ecdsa-sha256',
        };
    }
}
