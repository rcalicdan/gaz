<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzy_3_1;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_PMarzy_3_1Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzy_3_1 $p_PMarzy_3_1 Znacznik dostawy towarów używanych dla których podstawę opodatkowania stanowi marża, zgodnie z art. 120 ustawy, a faktura dokumentująca dostawę zawiera wyrazy "procedura marży - towary używane"
     */
    public function __construct(
        public readonly P_PMarzy_3_1 $p_PMarzy_3_1 = P_PMarzy_3_1::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzy_3_1Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_1Group');
        $dom->appendChild($p_PMarzy_3_1Group);

        $p_PMarzy_3_1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzy_3_1');
        $p_PMarzy_3_1->appendChild($dom->createTextNode((string) $this->p_PMarzy_3_1->value));

        $p_PMarzy_3_1Group->appendChild($p_PMarzy_3_1);

        return $dom;
    }
}
