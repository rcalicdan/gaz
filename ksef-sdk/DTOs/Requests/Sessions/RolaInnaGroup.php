<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\OpisRoli;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\RolaInna;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class RolaInnaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param RolaInna $rolaInna Znacznik innego podmiotu: 1 - Inny podmiot
     * @param OpisRoli $opisRoli Opis roli podmiotu - w przypadku wyboru roli jako Inny podmiot
     */
    public function __construct(
        public readonly RolaInna $rolaInna,
        public readonly OpisRoli $opisRoli,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaInnaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RolaInnaGroup');
        $dom->appendChild($rolaInnaGroup);

        $rolaInna = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RolaInna');
        $rolaInna->appendChild($dom->createTextNode((string) $this->rolaInna->value));

        $rolaInnaGroup->appendChild($rolaInna);

        $opisRoli = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisRoli');
        $opisRoli->appendChild($dom->createTextNode((string) $this->opisRoli));

        $rolaInnaGroup->appendChild($opisRoli);

        return $dom;
    }
}
