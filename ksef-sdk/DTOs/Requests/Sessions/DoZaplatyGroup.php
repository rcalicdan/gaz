<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DoZaplaty;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class DoZaplatyGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param DoZaplaty $doZaplaty Kwota należności do zapłaty równa polu P_15 powiększonemu o Obciazenia i pomniejszonemu o Odliczenia
     */
    public function __construct(
        public readonly DoZaplaty $doZaplaty,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $doZaplatyGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DoZaplatyGroup');
        $dom->appendChild($doZaplatyGroup);

        $doZaplaty = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DoZaplaty');
        $doZaplaty->appendChild($dom->createTextNode($this->doZaplaty->value));

        $doZaplatyGroup->appendChild($doZaplaty);

        return $dom;
    }
}
