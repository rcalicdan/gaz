<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Testdata\Person\Create;

use DateTime;
use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Pesel;

final class CreateRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly NIP $nip,
        public readonly Pesel $pesel,
        public readonly string $description,
        public readonly bool $isBailiff = false,
        public readonly Optional | DateTime | null $createdDate = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray();
    }
}
