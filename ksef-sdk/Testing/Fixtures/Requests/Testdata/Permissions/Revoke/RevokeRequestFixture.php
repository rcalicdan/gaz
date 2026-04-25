<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Permissions\Revoke;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class RevokeRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'contextIdentifier' => [
            'identifierGroup' => [
                'nip' => '1234567890',
            ],
        ],
        'authorizedIdentifier' => [
            'identifierGroup' => [
                'nip' => '1234567890',
            ],
        ],
    ];
}
