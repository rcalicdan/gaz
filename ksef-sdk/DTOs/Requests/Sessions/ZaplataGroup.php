<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DataZaplaty;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Zaplacono;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class ZaplataGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Zaplacono $zaplacono Znacznik informujący, że kwota należności wynikająca z faktury została zapłacona: 1 - zapłacono
     * @param DataZaplaty $dataZaplaty Data zapłaty, jeśli do wystawienia faktury płatność została dokonana
     */
    public function __construct(
        public readonly DataZaplaty $dataZaplaty,
        public readonly Zaplacono $zaplacono = Zaplacono::Default,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataGroup');
        $dom->appendChild($zaplataGroup);

        $zaplacono = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Zaplacono');
        $zaplacono->appendChild($dom->createTextNode((string) $this->zaplacono->value));

        $zaplataGroup->appendChild($zaplacono);

        $dataZaplaty = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataZaplaty');
        $dataZaplaty->appendChild($dom->createTextNode((string) $this->dataZaplaty));

        $zaplataGroup->appendChild($dataZaplaty);

        return $dom;
    }
}
