<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\NIP;

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

        $daneIdentyfikacyjne = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneIdentyfikacyjne');
        $dom->appendChild($daneIdentyfikacyjne);

        $nip = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NIP');
        $nip->appendChild($dom->createTextNode((string) $this->nip));

        $daneIdentyfikacyjne->appendChild($nip);

        $nazwa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Nazwa');
        $nazwa->appendChild($dom->createTextNode((string) $this->nazwa));

        $daneIdentyfikacyjne->appendChild($nazwa);

        return $dom;
    }
}
