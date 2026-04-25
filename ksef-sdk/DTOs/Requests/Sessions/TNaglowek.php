<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class TNaglowek extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, Kol>
     */
    public readonly array $kol;

    /**
     * @param array<int, Kol> $kol Zawartość pola
     */
    public function __construct(
        array $kol
    ) {
        Validator::validate([
            'kol' => $kol,
        ], [
            'kol' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->kol = $kol;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tNaglowek = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TNaglowek');
        $dom->appendChild($tNaglowek);

        foreach ($this->kol as $kol) {
            $kol = $dom->importNode($kol->toDom()->documentElement, true);

            $tNaglowek->appendChild($kol);
        }

        return $dom;
    }
}
