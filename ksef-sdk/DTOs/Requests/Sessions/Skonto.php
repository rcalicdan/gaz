<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\WarunkiSkonta;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\WysokoscSkonta;
use Rcalicdan\KSEFClient\Support\AbstractDTO;

final class Skonto extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @param WarunkiSkonta $warunkiSkonta Warunki, które nabywca powinien spełnić aby skorzystać ze skonta
     */
    public function __construct(
        public readonly WarunkiSkonta $warunkiSkonta,
        public readonly WysokoscSkonta $wysokoscSkonta
    ) {
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $skonto = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'Skonto');
        $dom->appendChild($skonto);

        $warunkiSkonta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WarunkiSkonta');
        $warunkiSkonta->appendChild($dom->createTextNode((string) $this->warunkiSkonta));

        $skonto->appendChild($warunkiSkonta);

        $wysokoscSkonta = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'WysokoscSkonta');
        $wysokoscSkonta->appendChild($dom->createTextNode((string) $this->wysokoscSkonta));

        $skonto->appendChild($wysokoscSkonta);

        return $dom;
    }
}
