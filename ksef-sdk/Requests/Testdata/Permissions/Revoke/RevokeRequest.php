<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Revoke;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions\AuthorizedIdentifier;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions\ContextIdentifier;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;

final class RevokeRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly ContextIdentifier $contextIdentifier,
        public readonly AuthorizedIdentifier $authorizedIdentifier,
    ) {
    }
}
