<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Persons\Country;

final class IdDocument extends AbstractDTO
{
    public function __construct(
        public readonly string $type,
        public readonly string $number,
        public readonly Country $country
    ) {
    }
}
