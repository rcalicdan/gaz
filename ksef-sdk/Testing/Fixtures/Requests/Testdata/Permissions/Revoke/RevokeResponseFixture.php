<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Permissions\Revoke;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class RevokeResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = '';
}
