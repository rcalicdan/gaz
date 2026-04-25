<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormaPlatnosci;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class FormaPlatnosciGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public readonly FormaPlatnosci $formaPlatnosci,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $formaPlatnosciGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FormaPlatnosciGroup');
        $dom->appendChild($formaPlatnosciGroup);

        $formaPlatnosci = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'FormaPlatnosci');
        $formaPlatnosci->appendChild($dom->createTextNode((string) $this->formaPlatnosci->value));

        $formaPlatnosciGroup->appendChild($formaPlatnosci);

        $dom->appendChild($formaPlatnosciGroup);

        return $dom;
    }
}
