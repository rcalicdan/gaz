<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Auth\Sessions\RevokeCurrent;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class RevokeCurrentResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 204;

    public string $data = '';
}
