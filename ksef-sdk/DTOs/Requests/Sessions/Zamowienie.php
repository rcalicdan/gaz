<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\WartoscZamowienia;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Zamowienie extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, ZamowienieWiersz>
     */
    public readonly array $zamowienieWiersz;

    /**
     * @param WartoscZamowienia $wartoscZamowienia Wartość zamówienia lub umowy z uwzględnieniem kwoty podatku
     * @param array<int, ZamowienieWiersz> $zamowienieWiersz Szczegółowe pozycje zamówienia lub umowy w walucie, w której wystawiono fakturę zaliczkową
     */
    public function __construct(
        public readonly WartoscZamowienia $wartoscZamowienia,
        array $zamowienieWiersz
    ) {
        Validator::validate([
            'zamowienieWiersz' => $zamowienieWiersz
        ], [
            'zamowienieWiersz' => [new MinRule(1), new MaxRule(10000)]
        ]);

        $this->zamowienieWiersz = $zamowienieWiersz;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zamowienie = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zamowienie');
        $dom->appendChild($zamowienie);

        $wartoscZamowienia = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WartoscZamowienia');
        $wartoscZamowienia->appendChild($dom->createTextNode((string) $this->wartoscZamowienia));

        $zamowienie->appendChild($wartoscZamowienia);

        foreach ($this->zamowienieWiersz as $zamowienieWiersz) {
            $zamowienieWiersz = $dom->importNode($zamowienieWiersz->toDom()->documentElement, true);

            $zamowienie->appendChild($zamowienieWiersz);
        }

        return $dom;
    }
}
