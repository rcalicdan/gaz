<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FakturaRR;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\DataDokumentu;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FakturaRR\NrDokumentu;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class DokumentZaplaty extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrDokumentu $nrDokumentu Dane identyfikacyjne dokumentu, o którym mowa w art. 116 ust. 6 pkt 3 ustawy lub dokumentu potwierdzającego dokonanie zapłaty zaliczki, o którym mowa w art. 116 ust. 9 pkt 1 i ust. 9b ustawy.
     * @param Optional|DataDokumentu $dataDokumentu Data dokumentu potwierdzającego dokonanie zapłaty lub zapłaty zaliczki
     */
    public function __construct(
        public readonly NrDokumentu $nrDokumentu,
        public readonly Optional | DataDokumentu $dataDokumentu = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $dokumentZaplaty = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'DokumentZaplaty');
        $dom->appendChild($dokumentZaplaty);

        $nrDokumentu = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'NrDokumentu');
        $nrDokumentu->appendChild($dom->createTextNode((string) $this->nrDokumentu));

        $dokumentZaplaty->appendChild($nrDokumentu);

        if ($this->dataDokumentu instanceof DataDokumentu) {
            $dataDokumentu = $dom->createElementNS((string) XmlNamespace::FaRr1->value, 'DataDokumentu');
            $dataDokumentu->appendChild($dom->createTextNode((string) $this->dataDokumentu));
            $dokumentZaplaty->appendChild($dataDokumentu);
        }

        return $dom;
    }
}
