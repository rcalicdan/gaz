<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions;

use Rcalicdan\KSEFClient\Contracts\Requests\Permissions\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Pesel;

final class AuthorizedIdentifierPeselGroup extends AbstractDTO implements IdentifierInterface
{
    public function __construct(
        public readonly Pesel $pesel,
    ) {
    }

    public function getIdentifier(): Pesel
    {
        return $this->pesel;
    }
}
