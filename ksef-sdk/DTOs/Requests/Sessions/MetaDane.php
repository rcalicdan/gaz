<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\ZKlucz;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\ZWartosc;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class MetaDane extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly ZKlucz $zKlucz,
        public readonly ZWartosc $zWartosc,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $metaDane = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'MetaDane');
        $dom->appendChild($metaDane);

        $zKlucz = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZKlucz');
        $zKlucz->appendChild($dom->createTextNode((string) $this->zKlucz));

        $metaDane->appendChild($zKlucz);

        $zWartosc = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZWartosc');
        $zWartosc->appendChild($dom->createTextNode((string) $this->zWartosc));

        $metaDane->appendChild($zWartosc);

        return $dom;
    }
}
