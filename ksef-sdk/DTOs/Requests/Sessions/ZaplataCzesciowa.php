<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use DOMElement;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DataZaplatyCzesciowej;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\KwotaZaplatyCzesciowej;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaplataCzesciowa extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param DataZaplatyCzesciowej $dataZaplatyCzesciowej Data zapłaty częściowej, jeśli do wystawienia faktury płatność częściowa została dokonana
     */
    public function __construct(
        public readonly KwotaZaplatyCzesciowej $kwotaZaplatyCzesciowej,
        public readonly DataZaplatyCzesciowej $dataZaplatyCzesciowej,
        public readonly Optional | FormaPlatnosciGroup | PlatnoscInnaGroup $platnoscGroup = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataCzesciowa');
        $dom->appendChild($zaplataCzesciowa);

        $kwotaZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KwotaZaplatyCzesciowej');
        $kwotaZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->kwotaZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($kwotaZaplatyCzesciowej);

        $dataZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataZaplatyCzesciowej');
        $dataZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->dataZaplatyCzesciowej));

        $zaplataCzesciowa->appendChild($dataZaplatyCzesciowej);

        if ( ! $this->platnoscGroup instanceof Optional) {
            /** @var DOMElement $platnoscGroup */
            $platnoscGroup = $this->platnoscGroup->toDom()->documentElement;

            foreach ($platnoscGroup->childNodes as $child) {
                $zaplataCzesciowa->appendChild($dom->importNode($child, true));
            }
        }

        return $dom;
    }
}
