<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class RozliczenieGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly DoZaplatyGroup|DoRozliczeniaGroup $doGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rozliczenieGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RozliczenieGroup');
        $dom->appendChild($rozliczenieGroup);

        /** @var DOMElement $doGroup */
        $doGroup = $this->doGroup->toDom()->documentElement;

        foreach ($doGroup->childNodes as $child) {
            $rozliczenieGroup->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
