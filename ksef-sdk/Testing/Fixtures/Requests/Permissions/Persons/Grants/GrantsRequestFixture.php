<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Persons\Grants;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class GrantsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'subjectIdentifierGroup' => [
            'pesel' => '15062788702',
        ],
        'permissions' => [
            'InvoiceRead',
            'InvoiceWrite',
            'Introspection',
            'CredentialsRead',
        ],
        'description' => 'Opis uprawnienia',
        'subjectDetails' => [
            'personById' => [
                'firstName' => 'Adam',
                'lastName' => 'Kowalski',
            ],
         ],
    ];
}
