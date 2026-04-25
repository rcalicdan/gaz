<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Context\Block;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;

final class BlockRequest extends AbstractRequest implements BodyInterface
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
