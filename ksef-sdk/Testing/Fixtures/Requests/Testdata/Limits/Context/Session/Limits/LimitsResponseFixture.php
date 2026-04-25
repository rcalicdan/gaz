<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Context\Session\Limits;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class LimitsResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    public string $data = '';
}
