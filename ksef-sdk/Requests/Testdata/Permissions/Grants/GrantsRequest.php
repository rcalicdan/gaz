<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Permissions\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions\AuthorizedIdentifier;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions\ContextIdentifier;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Permissions\Permission;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    /**
     * @param array<int, Permission> $permissions
     */
    public function __construct(
        public readonly ContextIdentifier $contextIdentifier,
        public readonly AuthorizedIdentifier $authorizedIdentifier,
        public readonly array $permissions,
    ) {
    }
}
