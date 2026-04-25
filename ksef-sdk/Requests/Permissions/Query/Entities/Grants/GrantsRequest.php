<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Grants;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierInternalIdGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Permissions\ContextIdentifierNipGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageSize;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly Optional | ContextIdentifierNipGroup | ContextIdentifierInternalIdGroup $contextIdentifierGroup = new Optional(),
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

        return $data;
    }
}
