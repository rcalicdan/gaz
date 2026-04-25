<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EmailPU;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\TelefonPU;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;

final class PodmiotUpowaznionyDaneKontaktowe extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly Optional | EmailPU $emailPU = new Optional(),
        public readonly Optional | TelefonPU $telefonPU = new Optional()
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $daneKontaktowe = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DaneKontaktowe');
        $dom->appendChild($daneKontaktowe);

        if ($this->emailPU instanceof EmailPU) {
            $emailPU = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'EmailPU');
            $emailPU->appendChild($dom->createTextNode((string) $this->emailPU));
            $daneKontaktowe->appendChild($emailPU);
        }

        if ($this->telefonPU instanceof TelefonPU) {
            $telefonPU = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TelefonPU');
            $telefonPU->appendChild($dom->createTextNode((string) $this->telefonPU));
            $daneKontaktowe->appendChild($telefonPU);
        }

        return $dom;
    }
}
