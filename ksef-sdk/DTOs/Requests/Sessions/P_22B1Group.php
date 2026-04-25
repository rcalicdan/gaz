<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_22B1;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_22B1Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22B1 $p_22B1 Jeśli dostawa dotyczy pojazdów lądowych, o których mowa w art. 2 pkt 10 lit. a ustawy - można podać numer VIN
     */
    public function __construct(
        public readonly P_22B1 $p_22B1,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22B1Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B1Group');
        $dom->appendChild($p_22B1Group);

        $p_22B1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22B1');
        $p_22B1->appendChild($dom->createTextNode($this->p_22B1->value));

        $p_22B1Group->appendChild($p_22B1);

        return $dom;
    }
}
