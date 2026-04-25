<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_19B;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class P_19BGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_19B $p_19B Jeśli pole P_19 równa się "1" - należy wskazać przepis dyrektywy 2006/112/WE, który zwalnia od podatku taką dostawę towarów lub takie świadczenie usług
     */
    public function __construct(
        public readonly P_19B $p_19B,
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_19BGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19BGroup');
        $dom->appendChild($p_19BGroup);

        $p_19B = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_19B');
        $p_19B->appendChild($dom->createTextNode((string) $this->p_19B));

        $p_19BGroup->appendChild($p_19B);

        return $dom;
    }
}
