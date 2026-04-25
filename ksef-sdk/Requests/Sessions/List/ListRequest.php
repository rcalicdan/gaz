<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\List;

use Rcalicdan\KSEFClient\Contracts\HeadersInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ContinuationToken;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateClosedFrom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateClosedTo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateCreatedFrom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateCreatedTo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateModifiedFrom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DateModifiedTo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\PageSize;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\SessionStatus;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\SessionType;

final class ListRequest extends AbstractRequest implements ParametersInterface, HeadersInterface
{
    /**
     * @param Optional|array<int, SessionStatus> $statuses
     */
    public function __construct(
        public readonly SessionType $sessionType,
        public readonly Optional | ReferenceNumber $referenceNumber = new Optional(),
        public readonly Optional | DateCreatedFrom $dateCreatedFrom = new Optional(),
        public readonly Optional | DateCreatedTo $dateCreatedTo = new Optional(),
        public readonly Optional | DateClosedFrom $dateClosedFrom = new Optional(),
        public readonly Optional | DateClosedTo $dateClosedTo = new Optional(),
        public readonly Optional | DateModifiedFrom $dateModifiedFrom = new Optional(),
        public readonly Optional | DateModifiedTo $dateModifiedTo = new Optional(),
        public readonly Optional | array $statuses = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
        public readonly Optional | ContinuationToken $continuationToken = new Optional(),
    ) {
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['pageSize', 'sessionType', 'referenceNumber', 'dateCreatedFrom', 'dateCreatedTo', 'dateClosedFrom', 'dateClosedTo', 'dateModifiedFrom', 'dateModifiedTo', 'statuses']);
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
