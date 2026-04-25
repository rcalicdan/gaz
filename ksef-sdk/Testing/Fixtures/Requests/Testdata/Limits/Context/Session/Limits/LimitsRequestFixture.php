<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Context\Session\Limits;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class LimitsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        "onlineSession" => [
            "maxInvoiceSizeInMB" => 0,
            "maxInvoiceWithAttachmentSizeInMB" => 0,
            "maxInvoices" => 0
        ],
        "batchSession" => [
            "maxInvoiceSizeInMB" => 0,
            "maxInvoiceWithAttachmentSizeInMB" => 0,
            "maxInvoices" => 0
        ]
    ];
}
