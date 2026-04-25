<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Limits\Context\Session\Limits;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Context\Session\BatchSession;
use Rcalicdan\KSEFClient\DTOs\Requests\Testdata\Limits\Context\Session\OnlineSession;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Concerns\HasToBody;

final class LimitsRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public readonly OnlineSession $onlineSession,
        public readonly BatchSession $batchSession,
    ) {
    }
}
