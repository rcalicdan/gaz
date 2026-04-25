<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\DTOs\Requests\Sessions;

use DOMDocument;
use Rcalicdan\KSEFClient\Contracts\DomSerializableInterface;
use Rcalicdan\KSEFClient\Support\AbstractDTO;
use Rcalicdan\KSEFClient\Support\Optional;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MaxRule;
use Rcalicdan\KSEFClient\Validator\Rules\Array\MinRule;
use Rcalicdan\KSEFClient\Validator\Validator;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\ZnacznikZaplatyCzesciowej;
use Rcalicdan\KSEFClient\ValueObjects\Requests\XmlNamespace;

final class ZaplataCzesciowaGroup extends AbstractDTO implements DomSerializableInterface
{
    /**
     * @var Optional|array<int, ZaplataCzesciowa>
     */
    public readonly Optional | array $zaplataCzesciowa;

    /**
     * @param Optional|array<int, ZaplataCzesciowa> $zaplataCzesciowa Dane zapłat częściowych
     * @param ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej Znacznik informujący, że należność wynikająca z faktury została zapłacona w części lub w całości: 1 - zapłacono w części; 2 - zapłacono w całości, jeśli należność wynikająca z faktury została zapłacona w dwóch lub więcej częściach, a ostatnia płatność jest płatnością końcową
     */
    public function __construct(
        Optional | array $zaplataCzesciowa = new Optional(),
        public readonly ZnacznikZaplatyCzesciowej $znacznikZaplatyCzesciowej = ZnacznikZaplatyCzesciowej::Default
    ) {
        Validator::validate([
            'zaplataCzesciowa' => $zaplataCzesciowa,
        ], [
            'zaplataCzesciowa' => [new MinRule(1), new MaxRule(100)],
        ]);

        $this->zaplataCzesciowa = $zaplataCzesciowa;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $zaplataCzesciowaGroup = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZaplataCzesciowaGroup');
        $dom->appendChild($zaplataCzesciowaGroup);

        $znacznikZaplatyCzesciowej = $dom->createElementNS((string) XmlNamespace::Fa3->value, 'ZnacznikZaplatyCzesciowej');
        $znacznikZaplatyCzesciowej->appendChild($dom->createTextNode((string) $this->znacznikZaplatyCzesciowej->value));

        $zaplataCzesciowaGroup->appendChild($znacznikZaplatyCzesciowej);

        if ( ! $this->zaplataCzesciowa instanceof Optional) {
            foreach ($this->zaplataCzesciowa as $zaplataCzesciowa) {
                $zaplataCzesciowa = $dom->importNode($zaplataCzesciowa->toDom()->documentElement, true);

                $zaplataCzesciowaGroup->appendChild($zaplataCzesciowa);
            }
        }

        return $dom;
    }
}
