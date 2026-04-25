<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Invoices\Exports;

use Rcalicdan\KSEFClient\DTOs\Requests\Invoices\Amount;
use Rcalicdan\KSEFClient\DTOs\Requests\Invoices\BuyerIdentifier;
use Rcalicdan\KSEFClient\DTOs\Requests\Invoices\DateRange;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\InvoiceNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\CurrencyCode;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\FormType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\InvoiceType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\InvoicingMode;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Invoices\SubjectType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\KsefNumber;

final class Filters extends AbstractDTO
{
    /**
     * @param Optional|array<int, CurrencyCode> $currencyCodes
     * @param Optional|array<int, InvoiceType> $invoiceTypes
     */
    public function __construct(
        public readonly SubjectType $subjectType,
        public readonly DateRange $dateRange,
        public readonly Optional | KsefNumber $ksefNumber = new Optional(),
        public readonly Optional | InvoiceNumber $invoiceNumber = new Optional(),
        public readonly Optional | Amount $amount = new Optional(),
        public readonly Optional | NIP $sellerNip = new Optional(),
        public readonly Optional | BuyerIdentifier $buyerIdentifier = new Optional(),
        public readonly Optional | array $currencyCodes = new Optional(),
        public readonly Optional | InvoicingMode $invoicingMode = new Optional(),
        public readonly Optional | bool $isSelfInvoicing = new Optional(),
        public readonly Optional | FormType $formType = new Optional(),
        public readonly Optional | array $invoiceTypes = new Optional(),
        public readonly Optional | bool $hasAttachment = new Optional(),
    ) {
    }
}
