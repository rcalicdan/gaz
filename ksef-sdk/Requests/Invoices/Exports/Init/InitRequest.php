<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Invoices\Exports\Init;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Invoices\Exports\Filters;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;

final class InitRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly Filters $filters,
    ) {
    }

    public function toBody(): array
    {
        return [
            'filters' => $this->filters->toArray()
        ];
    }
}
