<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\Authorizations\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\AuthorizedIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\AuthorizedIdentifierPeppolIdGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\AuthorizingIdentifierNipGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageSize;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\AuthorizationPermissionType;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Authorizations\QueryType;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param Optional|array<int, AuthorizationPermissionType> $permissionTypes
     */
    public function __construct(
        public readonly QueryType $queryType,
        public readonly Optional | AuthorizingIdentifierNipGroup $authorizingIdentifierGroup = new Optional(),
        public readonly Optional | AuthorizedIdentifierNipGroup | AuthorizedIdentifierPeppolIdGroup $authorizedIdentifierGroup = new Optional(),
        public readonly Optional | array $permissionTypes = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray();

        if ( ! $this->authorizingIdentifierGroup instanceof Optional) {
            $data['authorizingIdentifier'] = [
                'type' => $this->authorizingIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->authorizingIdentifierGroup->getIdentifier(),
            ];
        }

        if ( ! $this->authorizedIdentifierGroup instanceof Optional) {
            $data['authorizedIdentifier'] = [
                'type' => $this->authorizedIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->authorizedIdentifierGroup->getIdentifier(),
            ];
        }

        return $data;
    }
}
