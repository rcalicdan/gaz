<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Query\Entities\Grants;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class GrantsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'contextIdentifierGroup' => [
            'nip' => '3568707925',
        ],
        'pageOffset' => 0,
        'pageSize' => 10,
    ];
}
