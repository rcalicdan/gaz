<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions\Entities;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Entities\EntityPermissionType;

final class EntityPermission extends AbstractDTO
{
    public function __construct(
        public readonly EntityPermissionType $type,
        public readonly Optional | bool $canDelegate = new Optional(),
    ) {
    }
}
