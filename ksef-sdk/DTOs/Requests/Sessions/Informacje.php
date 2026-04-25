<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\StopkaFaktury;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;

final class Informacje extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly Optional | StopkaFaktury $stopkaFaktury = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $informacje = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Informacje');
        $dom->appendChild($informacje);

        if ($this->stopkaFaktury instanceof StopkaFaktury) {
            $stopkaFaktury = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'StopkaFaktury');
            $stopkaFaktury->appendChild($dom->createTextNode((string) $this->stopkaFaktury));
            $informacje->appendChild($stopkaFaktury);
        }

        return $dom;
    }
}
