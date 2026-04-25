<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Email;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Telefon;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;

final class DaneKontaktowe extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly Optional | Email $email = new Optional(),
        public readonly Optional | Telefon $telefon = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->email instanceof Email) {
            $email = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Email');
            $email->appendChild($dom->createTextNode((string) $this->email));
            $daneKontaktowe->appendChild($email);
        }

        if ($this->telefon instanceof Telefon) {
            $telefon = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Telefon');
            $telefon->appendChild($dom->createTextNode((string) $this->telefon));
            $daneKontaktowe->appendChild($telefon);
        }

        return $dom;
    }
}
