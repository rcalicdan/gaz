<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Auth\XadesSignature;

use Rcalicdan\KSEFClient\Contracts\HeadersInterface;
use Rcalicdan\KSEFClient\Contracts\ParametersInterface;
use Rcalicdan\KSEFClient\Contracts\XmlSerializableInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Auth\XadesSignature;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns\HasToHeaders;
use Rcalicdan\KSEFClient\Requests\Auth\XadesSignature\Concerns\HasToParameters;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Certificate;

final class XadesSignatureRequest extends AbstractRequest implements XmlSerializableInterface, ParametersInterface, HeadersInterface
{
    use HasToParameters;
    use HasToHeaders;

    public function __construct(
        public readonly Certificate $certificate,
        public readonly XadesSignature $xadesSignature,
        public readonly Optional | bool $verifyCertificateChain = new Optional(),
        public readonly Optional | bool $enforceXadesCompliance = new Optional(),
    ) {
    }

    public function toXml(): string
    {
        return $this->xadesSignature->toXml();
    }
}
