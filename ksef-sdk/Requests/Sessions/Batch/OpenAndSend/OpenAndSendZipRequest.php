<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Requests\Sessions\Batch\OpenAndSend\Concerns\HasToBody;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

final class OpenAndSendZipRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly FormCode $formCode,
        public readonly string $faktury,
        public readonly Optional | bool $offlineMode = new Optional(),
    ) {
    }
}
