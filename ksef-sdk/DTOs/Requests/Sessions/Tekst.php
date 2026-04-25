<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Akapit;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;

final class Tekst extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var array<int, Akapit>
     */
    public readonly array $akapit;

    /**
     * @param array<int, Akapit> $akapit Akapit stanowiący część tekstową bloku danych
     */
    public function __construct(
        array $akapit,
    ) {
        Validator::validate([
            'akapit' => $akapit,
        ], [
            'akapit' => [new MinRule(1), new MaxRule(10)],
        ]);

        $this->akapit = $akapit;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $tekst = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Tekst');
        $dom->appendChild($tekst);

        foreach ($this->akapit as $akapit) {
            $_akapit = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Akapit');
            $_akapit->appendChild($dom->createTextNode((string) $akapit));

            $tekst->appendChild($_akapit);
        }

        return $dom;
    }
}
