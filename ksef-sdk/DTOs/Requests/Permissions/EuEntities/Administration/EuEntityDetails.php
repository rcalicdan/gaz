<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Permissions\EuEntities\Administration;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\EuEntities\Administration\Address;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Permissions\EuEntities\Administration\FullName;

final class EuEntityDetails extends AbstractDTO implements BodyInterface
{
    public function __construct(
        public readonly FullName $fullName,
        public readonly Address $address
    ) {
    }

    public function toBody(): array
    {
        return [
            'fullName' => (string) $this->fullName,
            'address' => (string) $this->address,
        ];
    }
}
