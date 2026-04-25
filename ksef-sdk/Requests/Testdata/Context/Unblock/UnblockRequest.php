<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Context\Unblock;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;

final class UnblockRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly ContextIdentifierGroup $contextIdentifierGroup,
    ) {
    }

    public function toBody(): array
    {
        return [
            'contextIdentifier' => $this->contextIdentifierGroup->toBody(),
        ];
    }
}
