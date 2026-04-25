<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\OpisLadunku;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class OpisLadunkuGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param OpisLadunku $opisLadunku Rodzaj ładunku
     */
    public function __construct(
        public readonly OpisLadunku $opisLadunku
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $opisLadunkuGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisLadunkuGroup');
        $dom->appendChild($opisLadunkuGroup);

        $opisLadunku = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisLadunku');
        $opisLadunku->appendChild($dom->createTextNode((string) $this->opisLadunku->value));

        $opisLadunkuGroup->appendChild($opisLadunku);

        return $dom;
    }
}
