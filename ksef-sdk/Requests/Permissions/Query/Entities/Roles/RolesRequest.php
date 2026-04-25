<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Permissions\Query\Entities\Roles;

use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageOffset;
use Rcalicdan\KSEFClient\ValueObjects\Requests\PageSize;

final class RolesRequest extends AbstractRequest implements ParametersInterface
{
    public function __construct(
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toParameters(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: [
            'pageOffset',
            'pageSize',
        ]);
    }
}
