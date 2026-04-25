<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use DOMElement;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class FakturaZaliczkowa extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly NrKSeFZNGroup | NrKSeFFaZaliczkowejGroup $nrKSeFZNGroup
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $fakturaZaliczkowa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FakturaZaliczkowa');
        $dom->appendChild($fakturaZaliczkowa);

        /** @var DOMElement $nrKSeFZNGroup */
        $nrKSeFZNGroup = $this->nrKSeFZNGroup->toDom()->documentElement;

        foreach ($nrKSeFZNGroup->childNodes as $child) {
            $fakturaZaliczkowa->appendChild($dom->importNode($child, true));
        }

        return $dom;
    }
}
