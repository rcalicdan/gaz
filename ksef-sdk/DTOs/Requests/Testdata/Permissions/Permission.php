<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Description;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Testdata\Permissions\PermissionType;

final class Permission extends AbstractDTO
{
    public function __construct(
        public readonly Description $description,
        public readonly PermissionType $permissionType,
    ) {
    }
}
