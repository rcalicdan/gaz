<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Query\Authorizations\Grants;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class GrantsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'queryType' => 'Granted',
        'authorizingIdentifierGroup' => [
            'nip' => '3568707925',
        ],
        'authorizedIdentifierGroup' => [
            'nip' => '5687926712',
        ],
        'permissionTypes' => [
            'SelfInvoicing',
            'RRInvoicing',
        ],
        'pageOffset' => 0,
        'pageSize' => 10,
    ];
}
