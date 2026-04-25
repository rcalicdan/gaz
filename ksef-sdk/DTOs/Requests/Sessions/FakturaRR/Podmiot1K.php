<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Adres;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR\Podmiot1KDaneIdentyfikacyjne;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class Podmiot1K extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne Dane identyfikujące podatnika
     * @param Adres $adres Adres podatnika
     */
    public function __construct(
        public readonly Podmiot1KDaneIdentyfikacyjne $daneIdentyfikacyjne,
        public readonly Adres $adres,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $podmiot1K = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Podmiot1K');
        $dom->appendChild($podmiot1K);

        $daneIdentyfikacyjne = $dom->importNode($this->daneIdentyfikacyjne->toDom()->documentElement, true);

        $podmiot1K->appendChild($daneIdentyfikacyjne);

        $adres = $dom->importNode($this->adres->toDom()->documentElement, true);

        $podmiot1K->appendChild($adres);

        return $dom;
    }
}
