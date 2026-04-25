<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_22D1;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_22D;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;

final class P_22DGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param P_22D $p_22D Jeśli dostawa dotyczy statków powietrznych, o których mowa w art. 2 pkt 10 lit. c ustawy, należy podać liczbę godzin roboczych używania nowego środka transportu
     * @param Optional|P_22D1 $p_22D1 Jeśli dostawa dotyczy statków powietrznych, o których mowa w art. 2 pkt 10 lit. c ustawy, można podać numer fabryczny nowego środka transportu
     */
    public function __construct(
        public readonly P_22D $p_22D,
        public readonly Optional | P_22D1 $p_22D1 = new Optional(),
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $p_22DGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22DGroup');
        $dom->appendChild($p_22DGroup);

        $p_22D = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22D');
        $p_22D->appendChild($dom->createTextNode((string) $this->p_22D));

        $p_22DGroup->appendChild($p_22D);

        if ($this->p_22D1 instanceof P_22D1) {
            $p_22D1 = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'P_22D1');
            $p_22D1->appendChild($dom->createTextNode((string) $this->p_22D1));

            $p_22DGroup->appendChild($p_22D1);
        }

        return $dom;
    }
}
