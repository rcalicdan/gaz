<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Ilosc;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Jednostka;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\ZdarzeniePoczatkowe;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class TerminOpis extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly Ilosc $ilosc,
        public readonly Jednostka $jednostka,
        public readonly ZdarzeniePoczatkowe $zdarzeniePoczatkowe
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $terminOpis = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TerminOpis');
        $dom->appendChild($terminOpis);

        $ilosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Ilosc');
        $ilosc->appendChild($dom->createTextNode((string) $this->ilosc));

        $terminOpis->appendChild($ilosc);

        $jednostka = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Jednostka');
        $jednostka->appendChild($dom->createTextNode((string) $this->jednostka));

        $terminOpis->appendChild($jednostka);

        $zdarzeniePoczatkowe = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZdarzeniePoczatkowe');
        $zdarzeniePoczatkowe->appendChild($dom->createTextNode((string) $this->zdarzeniePoczatkowe));

        $terminOpis->appendChild($zdarzeniePoczatkowe);

        return $dom;
    }
}
