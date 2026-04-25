<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions;

use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class EntityByFp extends AbstractDTO
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $address,
    ) {
    }
}
