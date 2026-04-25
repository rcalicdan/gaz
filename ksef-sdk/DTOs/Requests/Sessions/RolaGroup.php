<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Rola;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class RolaGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly Rola $rola,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $rolaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'RolaGroup');
        $dom->appendChild($rolaGroup);

        $rola = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Rola');
        $rola->appendChild($dom->createTextNode((string) $this->rola->value));

        $rolaGroup->appendChild($rola);

        return $dom;
    }
}
