<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Tokens\Revoke;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class RevokeResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 204;

    public string $data = '';
}
