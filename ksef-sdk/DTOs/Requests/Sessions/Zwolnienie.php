<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Zwolnienie extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly P_19Group | P_19NGroup $p_19Group = new P_19NGroup(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zwolnienie = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zwolnienie');
        $dom->appendChild($zwolnienie);

        /** @var DOMElement $p_19Group */
        $p_19Group = $this->p_19Group->toDom()->documentElement;

        foreach ($p_19Group->childNodes as $child) {
            $zwolnienie->appendChild($dom->importNode($child, true));
        }

        $dom->appendChild($zwolnienie);

        return $dom;
    }
}
