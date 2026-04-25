<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\KsefUpo;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class KsefUpoRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'referenceNumber' => '20250625-EE-319D7EE000-B67F415CDC-2C',
        'ksefNumber' => '6422786434-20251002-010060F1FA83-CA'
    ];
}
