<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_6;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_6Group extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_6 $p_6 Data dokonania lub zakończenia dostawy towarów lub wykonania usługi lub data otrzymania zapłaty, o której mowa w art. 106b ust. 1 pkt 4 ustawy, o ile taka data jest określona i różni się od daty wystawienia faktury. Pole wypełnia się w przypadku, gdy dla wszystkich pozycji faktury data jest wspólna
     */
    public function __construct(
        public readonly P_6 $p_6,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p6Group = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6Group');
        $dom->appendChild($p6Group);

        $p6 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_6');
        $p6->appendChild($dom->createTextNode((string) $this->p_6));

        $p6Group->appendChild($p6);

        return $dom;
    }
}
