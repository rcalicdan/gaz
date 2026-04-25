<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\NKom;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Typ;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Kol extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Typ $typ Typ danych w nagłówku tabeli
     * @param NKom $nKom Zawartość pola
     */
    public function __construct(
        public readonly Typ $typ,
        public readonly NKom $nKom
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $kol = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Kol');
        $kol->setAttribute('Typ', (string) $this->typ->value);

        $dom->appendChild($kol);

        $nKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NKom');
        $nKom->appendChild($dom->createTextNode((string) $this->nKom));

        $kol->appendChild($nKom);

        return $dom;
    }
}
