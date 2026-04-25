<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Stopka extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, Informacje>
     */
    public readonly Optional | array $informacje;

    /**
     * @var Optional|array<int, Rejestry>
     */
    public readonly Optional | array $rejestry;

    /**
     * @param Optional|array<int, Informacje> $informacje Pozostałe dane
     * @param Optional|array<int, Rejestry> $rejestry
     */
    public function __construct(
        Optional | array $informacje = new Optional(),
        Optional | array $rejestry = new Optional()
    ) {
        Validator::validate([
            'informacje' => $informacje,
            'rejestry' => $rejestry
        ], [
            'informacje' => [new MaxRule(3)],
            'rejestry' => [new MaxRule(100)]
        ]);

        $this->informacje = $informacje;
        $this->rejestry = $rejestry;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $stopka = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Stopka');
        $dom->appendChild($stopka);

        if ( ! $this->informacje instanceof Optional) {
            foreach ($this->informacje as $informacje) {
                $informacje = $dom->importNode($informacje->toDom()->documentElement, true);
                $stopka->appendChild($informacje);
            }
        }

        if ( ! $this->rejestry instanceof Optional) {
            foreach ($this->rejestry as $rejestry) {
                $rejestry = $dom->importNode($rejestry->toDom()->documentElement, true);
                $stopka->appendChild($rejestry);
            }
        }

        return $dom;
    }
}
