<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Requests\Auth;

use Rcalicdan\KSEFClient\ValueObjects\InternalId;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\NipVatUe;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;

interface IdentifierInterface
{
    public function getIdentifier(): NIP | NipVatUe | InternalId | PeppolId;
}
