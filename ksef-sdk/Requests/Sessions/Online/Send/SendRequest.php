<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Requests\Sessions\Online\Send;

use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Contracts\XmlSerializableInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Faktura;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Faktura as FakturaRR;
use Rcalicdan\KSEFClient\Requests\AbstractRequest;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\HashOfCorrectedInvoice;

final class SendRequest extends AbstractRequest implements XmlSerializableInterface, BodyInterface
{
    public function __construct(
        public readonly ReferenceNumber $referenceNumber,
        public readonly Faktura | FakturaRR $faktura,
        public readonly FormCode $formCode = FormCode::Fa3,
        public readonly Optional | bool $offlineMode = new Optional(),
        public readonly Optional | HashOfCorrectedInvoice $hashOfCorrectedInvoice = new Optional()
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> */
        return $this->toArray(only: ['offlineMode', 'hashOfCorrectedInvoice']);
    }

    public function toXml(): string
    {
        return $this->faktura->toXml();
    }
}
