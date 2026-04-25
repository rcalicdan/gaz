<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_PMarzyN;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_PMarzyNGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_PMarzyN $p_PMarzyN Znacznik braku wystąpienia procedur marży, o których mowa w art. 119 lub art. 120 ustawy
     */
    public function __construct(
        public readonly P_PMarzyN $p_PMarzyN = P_PMarzyN::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_PMarzyNGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzyNGroup');
        $dom->appendChild($p_PMarzyNGroup);

        $p_PMarzyN = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_PMarzyN');
        $p_PMarzyN->appendChild($dom->createTextNode((string) $this->p_PMarzyN->value));

        $p_PMarzyNGroup->appendChild($p_PMarzyN);

        return $dom;
    }
}
