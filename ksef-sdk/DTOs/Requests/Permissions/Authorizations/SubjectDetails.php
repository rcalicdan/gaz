<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions\Authorizations;

use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class SubjectDetails extends AbstractDTO
{
    public function __construct(
        public readonly string $fullName,
    ) {
    }
}
