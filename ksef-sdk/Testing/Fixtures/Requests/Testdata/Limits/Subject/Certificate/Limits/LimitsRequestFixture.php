<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Testdata\Limits\Subject\Certificate\Limits;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

final class LimitsRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'subjectIdentifierType' => 'Nip',
        'enrollment' => [
            'maxEnrollments' => 0
        ],
        'certificate' => [
            'maxCertificates' => 0
        ]
    ];
}
