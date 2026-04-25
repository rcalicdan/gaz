<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Zalacznik extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, BlokDanych>
     */
    public readonly array $blokDanych;

    /**
     * @param array<int, BlokDanych> $blokDanych Szczegółowe dane załącznika do faktury (bloki danych)
     */
    public function __construct(
        array $blokDanych,
    ) {
        Validator::validate([
            'blokDanych' => $blokDanych,
        ], [
            'blokDanych' => [new MinRule(1), new MaxRule(1000)],
        ]);

        $this->blokDanych = $blokDanych;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zalacznik = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zalacznik');
        $dom->appendChild($zalacznik);

        foreach ($this->blokDanych as $blokDanych) {
            $blokDanych = $dom->importNode($blokDanych->toDom()->documentElement, true);

            $zalacznik->appendChild($blokDanych);
        }

        return $dom;
    }
}
