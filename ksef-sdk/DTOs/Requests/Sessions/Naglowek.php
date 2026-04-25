<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DateTimeImmutable;
use DateTimeZone;
use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\DataWytworzeniaFa;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\SystemInfo;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class Naglowek extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param Optional|SystemInfo $systemInfo Nazwa systemu teleinformatycznego, z którego korzysta podatnik
     */
    public function __construct(
        public readonly FormCode $wariantFormularza = FormCode::Fa3,
        public readonly DataWytworzeniaFa $dataWytworzeniaFa = new DataWytworzeniaFa(new DateTimeImmutable('now', new DateTimeZone('UTC'))),
        public readonly Optional | SystemInfo $systemInfo = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $naglowek = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Naglowek');
        $dom->appendChild($naglowek);

        $kodFormularza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'KodFormularza');
        $kodFormularza->setAttribute('kodSystemowy', (string) $this->wariantFormularza->value);
        $kodFormularza->setAttribute('wersjaSchemy', $this->wariantFormularza->getSchemaVersion());
        $kodFormularza->appendChild($dom->createTextNode('FA'));

        $naglowek->appendChild($kodFormularza);

        $wariantFormularza = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WariantFormularza');
        $wariantFormularza->appendChild($dom->createTextNode($this->wariantFormularza->getWariantFormularza()));

        $naglowek->appendChild($wariantFormularza);

        $dataWytworzeniaFa = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'DataWytworzeniaFa');
        $dataWytworzeniaFa->appendChild($dom->createTextNode((string) $this->dataWytworzeniaFa));

        $naglowek->appendChild($dataWytworzeniaFa);

        if ($this->systemInfo instanceof SystemInfo) {
            $systemInfo = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'SystemInfo');
            $systemInfo->appendChild($dom->createTextNode((string) $this->systemInfo));
            $naglowek->appendChild($systemInfo);
        }

        return $dom;
    }
}
