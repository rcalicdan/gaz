<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Fingerprint;

final class AuthorizedIdentifierFingerprintGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly Fingerprint $fingerprint,
    ) {
    }

    public function getIdentifier(): Fingerprint
    {
        return $this->fingerprint;
    }
}
