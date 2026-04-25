<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Exception;

use Throwable;

interface ExceptionHandlerInterface
{
    public function handle(Throwable $throwable): Throwable;
}
