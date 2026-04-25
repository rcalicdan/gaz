<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\Close;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class CloseResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 204;

    public string $data = "";
}
