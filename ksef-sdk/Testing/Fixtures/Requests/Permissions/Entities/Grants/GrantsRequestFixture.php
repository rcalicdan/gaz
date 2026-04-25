<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Entities\Grants;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class GrantsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'subjectIdentifierGroup' => [
            'nip' => '7762811692',
        ],
        'permissions' => [
            [
                'type' => 'InvoiceRead',
                'canDelegate' => true,
            ],
            [
                'type' => 'InvoiceWrite',
                'canDelegate' => true,
            ],
        ],
        'description' => 'Opis uprawnienia',
        'subjectDetails' => [
            'fullName' => 'Adam Kowalski',
        ],
    ];
}
