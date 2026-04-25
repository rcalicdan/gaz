<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFN;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class NrKSeFNGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrKSeFN $nrKSeFN Znacznik faktury korygowanej wystawionej poza KSeF
     */
    public function __construct(
        public readonly NrKSeFN $nrKSeFN = NrKSeFN::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFNGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFNGroup');
        $dom->appendChild($nrKSeFNGroup);

        $nrKSeFN = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFN');
        $nrKSeFN->appendChild($dom->createTextNode((string) $this->nrKSeFN->value));

        $nrKSeFNGroup->appendChild($nrKSeFN);

        return $dom;
    }
}
