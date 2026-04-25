<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Attachment\Approve;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class ApproveResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = '';
}
