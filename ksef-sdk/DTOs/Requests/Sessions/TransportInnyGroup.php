<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\OpisInnegoTransportu;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\TransportInny;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class TransportInnyGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param TransportInny $transportInny Znacznik innego rodzaju transportu: 1 - inny rodzaj transportu
     */
    public function __construct(
        public readonly OpisInnegoTransportu $opisInnegoTransportu,
        public readonly TransportInny $transportInny = TransportInny::Default
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $transportInnyGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TransportInnyGroup');
        $dom->appendChild($transportInnyGroup);

        $transportInny = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'TransportInny');
        $transportInny->appendChild($dom->createTextNode((string) $this->transportInny->value));

        $transportInnyGroup->appendChild($transportInny);

        $opisInnegoTransportu = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'OpisInnegoTransportu');
        $opisInnegoTransportu->appendChild($dom->createTextNode((string) $this->opisInnegoTransportu));

        $transportInnyGroup->appendChild($opisInnegoTransportu);

        return $dom;
    }
}
