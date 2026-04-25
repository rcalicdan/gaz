<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Tokens\Status;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 202;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
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
    ];
}
