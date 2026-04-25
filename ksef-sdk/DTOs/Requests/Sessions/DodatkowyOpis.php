<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Klucz;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\NrWiersza;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Wartosc;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;

final class DodatkowyOpis extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrWiersza|Optional $nrWiersza Numer wiersza podany w polu NrWierszaFa lub NrWierszaZam, jeśli informacja odnosi się wyłącznie do danej pozycji faktury
     */
    public function __construct(
        public readonly Klucz $klucz,
        public readonly Wartosc $wartosc,
        public readonly Optional | NrWiersza $nrWiersza = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $dodatkowyOpis = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DodatkowyOpis');
        $dom->appendChild($dodatkowyOpis);

        if ($this->nrWiersza instanceof NrWiersza) {
            $nrWiersza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrWiersza');
            $nrWiersza->appendChild($dom->createTextNode((string) $this->nrWiersza));
            $dodatkowyOpis->appendChild($nrWiersza);
        }

        $klucz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Klucz');
        $klucz->appendChild($dom->createTextNode((string) $this->klucz));

        $dodatkowyOpis->appendChild($klucz);

        $wartosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Wartosc');
        $wartosc->appendChild($dom->createTextNode((string) $this->wartosc));

        $dodatkowyOpis->appendChild($wartosc);

        $dom->appendChild($dodatkowyOpis);

        return $dom;
    }
}
