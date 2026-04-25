<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\Personal\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierInternalIdGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierNipGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\TargetIdentifierTypeGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageSize;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\PermissionState;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\Query\Personal\PersonalPermissionType;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param Optional|array<int, PersonalPermissionType> $permissionTypes
     */
    public function __construct(
        public readonly Optional | ContextIdentifierNipGroup $contextIdentifierGroup = new Optional(),
        public readonly Optional | TargetIdentifierNipGroup | TargetIdentifierInternalIdGroup | TargetIdentifierTypeGroup $targetIdentifierGroup = new Optional(),
        public readonly Optional | array $permissionTypes = new Optional(),
        public readonly Optional | PermissionState $permissionState = new Optional(),
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray();

        if ( ! $this->contextIdentifierGroup instanceof Optional) {
            $data['contextIdentifier'] = [
                'type' => $this->contextIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->contextIdentifierGroup->getIdentifier(),
            ];
        }

        if ( ! $this->targetIdentifierGroup instanceof Optional) {
            $data['targetIdentifier'] = [
                'type' => $this->targetIdentifierGroup->getIdentifier()->getType(),
                ...($this->targetIdentifierGroup instanceof TargetIdentifierTypeGroup ? [] : [
                    'value' => (string) $this->targetIdentifierGroup->getIdentifier()
                ]),
            ];
        }

        return $data;
    }
}
