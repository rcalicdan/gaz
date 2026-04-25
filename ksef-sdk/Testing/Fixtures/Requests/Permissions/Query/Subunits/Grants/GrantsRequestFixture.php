<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Query\Subunits\Grants;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class GrantsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'subunitIdentifierGroup' => [
            'internalId' => '7762811692-12345',
        ],
        'pageOffset' => 0,
        'pageSize' => 10,
    ];
}
