<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Permissions\Attachments\Status;

use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractResponseFixture;

final class StatusResponseFixture extends AbstractResponseFixture
{
    public int $statusCode = 200;

    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'isAttachmentAllowed' => true,
        'revokedDate' => '2019-08-24T14:15:22Z'
    ];
}
