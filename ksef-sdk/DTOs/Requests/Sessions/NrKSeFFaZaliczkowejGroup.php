<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\NrKSeFFaZaliczkowej;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class NrKSeFFaZaliczkowejGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej Numer identyfikujący fakturę zaliczkową w KSeF. Pole obowiązkowe w przypadku, gdy faktura zaliczkowa była wystawiona za pomocą KSeF
     */
    public function __construct(
        public readonly NrKSeFFaZaliczkowej $nrKSeFFaZaliczkowej
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $nrKSeFFaZaliczkowejGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFFaZaliczkowejGroup');
        $dom->appendChild($nrKSeFFaZaliczkowejGroup);

        $nrKSeFFaZaliczkowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'NrKSeFFaZaliczkowej');
        $nrKSeFFaZaliczkowej->appendChild($dom->createTextNode($this->nrKSeFFaZaliczkowej->value));

        $nrKSeFFaZaliczkowejGroup->appendChild($nrKSeFFaZaliczkowej);

        return $dom;
    }
}
