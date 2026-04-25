<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\SKom;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Suma extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, SKom>
     */
    public readonly array $sKom;

    /**
     * @param array<int, SKom> $sKom Zawartość pola
     */
    public function __construct(
        array $sKom,
    ) {
        Validator::validate([
            'sKom' => $sKom,
        ], [
            'sKom' => [new MinRule(1), new MaxRule(20)],
        ]);

        $this->sKom = $sKom;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $suma = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Suma');
        $dom->appendChild($suma);

        foreach ($this->sKom as $sKom) {
            $_sKom = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SKom');
            $_sKom->appendChild($dom->createTextNode((string) $sKom));

            $suma->appendChild($_sKom);
        }

        return $dom;
    }
}
