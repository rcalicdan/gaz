<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\IDWew;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class IDWewGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param IDWew $iDWew Identyfikator wewnętrzny z NIP
     */
    public function __construct(
        public readonly IDWew $iDWew,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $iDWewGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDWewGroup');
        $dom->appendChild($iDWewGroup);

        $iDWew = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'IDWew');
        $iDWew->appendChild($dom->createTextNode($this->iDWew->value));

        $iDWewGroup->appendChild($iDWew);

        return $dom;
    }
}
