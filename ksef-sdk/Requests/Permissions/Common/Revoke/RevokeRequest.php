<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Common\Revoke;

use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\PermissionId;

final class RevokeRequest extends AbstractRequest
{
    public function __construct(
        public readonly PermissionId $permissionId
    ) {
    }
}
