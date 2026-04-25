<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\OpenAndSend;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class SendResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 201;

    public string $data = '';
}
