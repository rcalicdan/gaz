<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Tokens\List;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class ListResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'continuationToken' => 'string',
        'tokens' => [
            [
                'referenceNumber' => 'string',
                'authorIdentifier' => [
                    'type' => 'Nip',
                    'value' => 'string',
                ],
                'contextIdentifier' => [
                    'type' => 'Nip',
                    'value' => 'string',
                ],
                'description' => 'string',
                'requestedPermissions' => [
                    'InvoiceRead',
                ],
                'dateCreated' => '2019-08-24T14:15:22Z',
                'lastUseDate' => '2019-08-24T14:15:22Z',
                'status' => 'Pending',
                'statusDetails' => [
                    'string',
                ],
            ],
        ],
    ];
}
