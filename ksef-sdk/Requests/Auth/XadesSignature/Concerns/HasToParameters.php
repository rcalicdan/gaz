<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns;

use Rcalicdan\KSEFClient\Support\Optional;

/**
 * @property-read Optional | bool $verifyCertificateChain
 */
trait HasToParameters
{
    public function toParameters(): array
    {
        $parameters = [];

        if ( ! $this->verifyCertificateChain instanceof Optional) {
            $parameters['verifyCertificateChain'] = $this->verifyCertificateChain ? "true" : "false";
        }

        return $parameters;
    }
}
