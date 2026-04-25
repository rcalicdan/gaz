<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Exceptions;

use Rcalicdan\KSEFClient\Exceptions\AbstractException;

/**
 * @property-read array{message: string, values: array<int, mixed>} $context
 */
class RuleValidationException extends AbstractException
{
}
