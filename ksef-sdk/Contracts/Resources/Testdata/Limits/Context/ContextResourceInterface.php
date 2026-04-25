<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Context;

use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Context\Session\SessionResourceInterface;

interface ContextResourceInterface
{
    public function session(): SessionResourceInterface;
}
