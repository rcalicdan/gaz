<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Invoices\Failed;

use Rcalicdan\KSEFClient\Contracts\HeadersInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ContinuationToken;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\PageSize;

final class FailedRequest extends AbstractRequest implements ParametersInterface, HeadersInterface
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | ContinuationToken $continuationToken = new Optional(),
    ) {
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize']);
    }

    public function toHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            ...($this->continuationToken instanceof ContinuationToken ? [
                'x-continuation-token' => $this->continuationToken->value
            ] : [])
        ];
    }
}
