<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Auth;

use DOMDocument;
use DOMElement;
use Rcalicdan\KSEFClient\Contracts\BodyInterface;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\InternalId;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\NipVatUe;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ContextIdentifierGroup extends AbstractDTO implements DomSerializableInterface, BodyInterface
{
    public function __construct(
        public readonly ContextIdentifierNipGroup | ContextIdentifierNipVatUeGroup | ContextIdentifierInternalIdGroup | ContextIdentifierPeppolIdGroup $identifierGroup
    ) {
    }

    public static function fromIdentifier(NIP | NipVatUe | InternalId | PeppolId $identifier): self
    {
        return match (true) {
            $identifier instanceof NIP => new self(new ContextIdentifierNipGroup($identifier)),
            $identifier instanceof NipVatUe => new self(new ContextIdentifierNipVatUeGroup($identifier)),
            $identifier instanceof InternalId => new self(new ContextIdentifierInternalIdGroup($identifier)),
            $identifier instanceof PeppolId => new self(new ContextIdentifierPeppolIdGroup($identifier)),
        };
    }

    public function toBody(): array
    {
        return [
            'type' => $this->identifierGroup->getIdentifier()->getType(),
            'value' => (string) $this->identifierGroup->getIdentifier()
        ];
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierGroup = $dom->createElementNS((string) XmlNamespace::Auth->value, 'ContextIdentifierGroup');
        $dom->appendChild($contextIdentifierGroup);

        /** @var DOMElement $identifierGroup */
        $identifierGroup = $dom->importNode($this->identifierGroup->toDom()->documentElement, true);

        foreach ($identifierGroup->childNodes as $child) {
            $contextIdentifierGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
