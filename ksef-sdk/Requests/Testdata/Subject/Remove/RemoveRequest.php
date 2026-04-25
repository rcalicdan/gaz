<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Subject\Remove;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

final class RemoveRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly NIP $subjectNip,
    ) {
    }
}
