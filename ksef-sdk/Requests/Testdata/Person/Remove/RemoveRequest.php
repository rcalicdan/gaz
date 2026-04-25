<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Person\Remove;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

final class RemoveRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly NIP $nip,
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray();
    }
}
