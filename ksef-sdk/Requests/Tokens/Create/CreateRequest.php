<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Tokens\Create;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Tokens\TokenPermissionType;

final class CreateRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param array<int, TokenPermissionType> $permissions
     */
    public function __construct(
        public readonly array $permissions,
        public readonly string $description,
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray();
    }
}
