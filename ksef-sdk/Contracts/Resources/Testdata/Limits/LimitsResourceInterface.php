<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits;

use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Context\ContextResourceInterface;
use Rcalicdan\KSEFClient\Contracts\Resources\Testdata\Limits\Subject\SubjectResourceInterface;

interface LimitsResourceInterface
{
    public function context(): ContextResourceInterface;

    public function subject(): SubjectResourceInterface;
}
