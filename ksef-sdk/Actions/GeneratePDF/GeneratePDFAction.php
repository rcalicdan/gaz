<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\GeneratePDF;

use Rcalicdan\KSEFClient\Actions\AbstractAction;
use Rcalicdan\KSEFClient\DTOs\QRCodes;
use Rcalicdan\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;
use Rcalicdan\KSEFClient\ValueObjects\Requests\KsefNumber;

final class GeneratePDFAction extends AbstractAction
{
    public function __construct(
        public readonly KsefFeInvoiceConverterPath $ksefFeInvoiceConverterPath,
        public readonly string $nodePath = 'node',
        public readonly ?string $invoiceDocument = null,
        public readonly ?string $upoDocument = null,
        public readonly ?string $confirmationDocument = null,
        public readonly ?KsefNumber $ksefNumber = null,
        public readonly ?QRCodes $qrCodes = null,
    ) {
    }
}
