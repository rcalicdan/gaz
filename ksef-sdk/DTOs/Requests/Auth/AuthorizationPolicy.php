<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Auth;

use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class AuthorizationPolicy extends AbstractDTO
{
    public function __construct(
        public readonly AllowedIps $allowedIps,
    ) {
    }
}
