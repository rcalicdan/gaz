<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Operations\Status;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'status' => [
            'code' => 200,
            'description' => 'Operacja zakończona sukcesem',
        ]
    ];
}
