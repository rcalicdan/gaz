<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Invoices\Download;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class DownloadResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = 'invoice';

    public function toContents(): string
    {
        return $this->data;
    }
}
