<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Actions\GenerateQRCodes;

use DateTimeInterface;
use Rcalicdan\KSEFClient\Actions\AbstractAction;
use Rcalicdan\KSEFClient\Contracts\Actions\GenerateQRCodes\InvoiceHashInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use Rcalicdan\KSEFClient\ValueObjects\Certificate;
use Rcalicdan\KSEFClient\ValueObjects\CertificateSerialNumber;
use Rcalicdan\KSEFClient\ValueObjects\Mode;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\KsefNumber;

final class GenerateQRCodesByInvoiceHashAction extends AbstractAction implements InvoiceHashInterface
{
    /**
     * @param null|CertificateSerialNumber $certificateSerialNumber {@deprecated} This parameter is no longer used and will be removed in the next major version. The certificate serial number is now obtained directly from the Certificate object.
     */
    public function __construct(
        public readonly NIP $nip,
        public readonly DateTimeInterface $invoiceCreatedAt,
        public readonly string $invoiceHash,
        public readonly Mode $mode = Mode::Production,
        public readonly ?KsefNumber $ksefNumber = null,
        public readonly ?Certificate $certificate = null,
        public readonly ?CertificateSerialNumber $certificateSerialNumber = null,
        public readonly ?ContextIdentifierGroup $contextIdentifierGroup = null,
        public readonly bool $captions = true
    ) {
    }

    public function getInvoiceHash(): string
    {
        return $this->invoiceHash;
    }
}
