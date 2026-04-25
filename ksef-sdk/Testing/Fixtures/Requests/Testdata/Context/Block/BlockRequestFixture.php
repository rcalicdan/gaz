<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Context\Block;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class BlockRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'contextIdentifierGroup' => [
            'identifierGroup' => [
                'nip' => '1234567890',
            ],
        ],
    ];
}
