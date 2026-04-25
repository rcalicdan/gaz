<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions;

use Rcalicdan\KSEFClient\Exceptions\AbstractException;

/**
 * @property-read object{status: object{code: int, description: string, details?: array<int, string>, extensions?: object}} $context
 */
class StatusException extends AbstractException
{
}
