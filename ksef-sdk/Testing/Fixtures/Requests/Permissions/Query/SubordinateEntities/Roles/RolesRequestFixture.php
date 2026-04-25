<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Query\SubordinateEntities\Roles;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class RolesRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'subordinateEntityIdentifierGroup' => [
            'nip' => '7762811692',
        ],
        'pageOffset' => 0,
        'pageSize' => 10,
    ];
}
