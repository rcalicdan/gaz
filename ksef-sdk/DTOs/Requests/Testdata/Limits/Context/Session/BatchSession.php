<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Context\Session;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Number\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Number\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class BatchSession extends AbstractDTO
{
    public function __construct(
        public readonly int $maxInvoiceSizeInMB,
        public readonly int $maxInvoiceWithAttachmentSizeInMB,
        public readonly int $maxInvoices
    ) {
        Validator::validate($this->toArray(), [
            'maxInvoiceSizeInMB' => [new MinRule(0), new MaxRule(5)],
            'maxInvoiceWithAttachmentSizeInMB' => [new MinRule(0), new MaxRule(10)],
            'maxInvoices' => [new MinRule(0), new MaxRule(100000)],
        ]);
    }
}
