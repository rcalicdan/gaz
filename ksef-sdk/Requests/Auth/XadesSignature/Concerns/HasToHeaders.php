<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns;

use Rcalicdan\KSEFClient\Support\Optional;

/**
 * @property-read Optional | bool $enforceXadesCompliance
 */
trait HasToHeaders
{
    public function toHeaders(): array
    {
        if ($this->enforceXadesCompliance === true) {
            return [
                'X-KSeF-Feature' => 'enforce-xades-compliance',
            ];
        }

        return [];
    }
}
