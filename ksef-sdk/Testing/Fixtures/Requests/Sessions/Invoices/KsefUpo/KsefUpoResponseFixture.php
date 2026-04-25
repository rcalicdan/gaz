<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Invoices\KsefUpo;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class KsefUpoResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = 'upo';

    public function toContents(): string
    {
        return $this->data;
    }
}
