<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum TargetIdentifierType: string implements EnumInterface
{
    case AllPartners = 'AllPartners';

    public function getType(): string
    {
        return $this->value;
    }
}
