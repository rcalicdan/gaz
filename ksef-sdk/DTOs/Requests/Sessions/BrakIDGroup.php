<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\BrakID;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class BrakIDGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param BrakID $brakID Podmiot nie posiada identyfikatora podatkowego lub identyfikator nie występuje na fakturze: 1- tak
     */
    public function __construct(
        public readonly BrakID $brakID,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $brakIDGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'BrakIDGroup');
        $dom->appendChild($brakIDGroup);

        $brakID = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'BrakID');
        $brakID->appendChild($dom->createTextNode((string) $this->brakID->value));

        $brakIDGroup->appendChild($brakID);

        return $dom;
    }
}
