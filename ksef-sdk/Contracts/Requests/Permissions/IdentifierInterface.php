<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Contracts\Requests\Permissions;

use Rcalicdan\KSEFClient\ValueObjects\Fingerprint;
use Rcalicdan\KSEFClient\ValueObjects\InternalId;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\NipVatUe;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;
use Rcalicdan\KSEFClient\ValueObjects\Pesel;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\Persons\AuthorIdentifierType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\TargetIdentifierType;

interface IdentifierInterface
{
    public function getIdentifier(): NIP | NipVatUe | Pesel | Fingerprint | PeppolId | InternalId | TargetIdentifierType | AuthorIdentifierType;
}
