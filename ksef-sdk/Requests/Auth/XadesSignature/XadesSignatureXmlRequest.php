<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\XadesSignature;

use Rcalicdan\KSEFClient\Contracts\HeadersInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Contracts\XmlSerializableInterface;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns\HasToHeaders;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns\HasToParameters;
use Rcalicdan\KSEFClient\Support\Optional;

final class XadesSignatureXmlRequest extends AbstractRequest implements XmlSerializableInterface, ParametersInterface, HeadersInterface
{
    use HasToParameters;
    use HasToHeaders;

    public function __construct(
        public readonly string $xadesSignature,
        public readonly Optional | bool $verifyCertificateChain = new Optional(),
        public readonly Optional | bool $enforceXadesCompliance = new Optional(),
    ) {
    }

    public function toXml(): string
    {
        return $this->xadesSignature;
    }
}
