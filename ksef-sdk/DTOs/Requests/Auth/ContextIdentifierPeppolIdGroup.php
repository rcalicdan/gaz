<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Auth;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Contracts\Requests\Auth\IdentifierInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\PeppolId;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ContextIdentifierPeppolIdGroup extends AbstractDTO implements DomSerializableInterface, IdentifierInterface
{
    public function __construct(
        public readonly PeppolId $peppolId,
    ) {
    }

    public function getIdentifier(): PeppolId
    {
        return $this->peppolId;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierPeppolIdGroup = $dom->createElementNS((string) XmlNamespace::Auth->value, 'ContextIdentifierPeppolIdGroup');
        $dom->appendChild($contextIdentifierPeppolIdGroup);

        $peppolId = $dom->createElementNS((string) XmlNamespace::Auth->value, 'PeppolId');
        $peppolId->appendChild($dom->createTextNode((string) $this->peppolId));

        $contextIdentifierPeppolIdGroup->appendChild($peppolId);

        return $dom;
    }
}
