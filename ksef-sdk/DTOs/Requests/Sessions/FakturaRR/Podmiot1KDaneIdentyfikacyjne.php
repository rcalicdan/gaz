<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Podmiot1KDaneIdentyfikacyjne extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly NIP $nip,
        public readonly Nazwa $nazwa
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneIdentyfikacyjne = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        return $dom;
    }
}
