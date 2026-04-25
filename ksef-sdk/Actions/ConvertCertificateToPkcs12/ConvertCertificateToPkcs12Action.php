<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\ConvertCertificateToPkcs12;

use SensitiveParameter;
use Rcalicdan\KSEFClient\Actions\AbstractAction;
use Rcalicdan\KSEFClient\ValueObjects\Certificate;

final class ConvertCertificateToPkcs12Action extends AbstractAction
{
    public function __construct(
        public readonly Certificate $certificate,
        #[SensitiveParameter] public readonly string $passphrase
    ) {
    }
}
