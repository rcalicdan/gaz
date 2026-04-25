<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_3_3;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_PMarzy_3_3Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_3_3 $p_PMarzy_3_3 Znacznik dostawy przedmiotów kolekcjonerskich i antyków, dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - przedmioty kolekcjonerskie i antyki"
     */
    public function __construct(
        public readonly P_PMarzy_3_3 $p_PMarzy_3_3 = P_PMarzy_3_3::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_3_3Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_3Group');
        $dom->appendChild($p_PMarzy_3_3Group);

        $p_PMarzy_3_3 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_3');
        $p_PMarzy_3_3->appendChild($dom->createTextNode((string) $this->p_PMarzy_3_3->value));

        $p_PMarzy_3_3Group->appendChild($p_PMarzy_3_3);

        return $dom;
    }
}
