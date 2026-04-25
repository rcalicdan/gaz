<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DoRozliczenia;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class DoRozliczeniaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param DoRozliczenia $doRozliczenia Kwota nadpłacona do rozliczenia/zwrotu
     */
    public function __construct(
        public readonly DoRozliczenia $doRozliczenia,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $doRozliczeniaGroup = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'DoRozliczeniaGroup');
        $dom->appendChild($doRozliczeniaGroup);

        $doRozliczenia = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'DoRozliczenia');
        $doRozliczenia->appendChild($dom->createTextNode($this->doRozliczenia->value));

        $doRozliczeniaGroup->appendChild($doRozliczenia);

        return $dom;
    }
}
