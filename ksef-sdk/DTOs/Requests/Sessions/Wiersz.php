<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\WKom;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Wiersz extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, WKom>
     */
    public readonly array $wKom;

    /**
     * @param array<int, WKom> $wKom Zawartość pola
     */
    public function __construct(
        array $wKom,
    ) {
        Validator::validate([
            'wKom' => $wKom,
        ], [
            'wKom' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->wKom = $wKom;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $wiersz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Wiersz');
        $dom->appendChild($wiersz);

        foreach ($this->wKom as $wKom) {
            $_wKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WKom');
            $_wKom->appendChild($dom->createTextNode((string) $wKom));

            $wiersz->appendChild($_wKom);
        }

        return $dom;
    }
}
