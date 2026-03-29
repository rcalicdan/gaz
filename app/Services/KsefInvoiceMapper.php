<?php

namespace App\Services;

use App\Models\Invoice;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Adres;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Adnotacje;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Faktura;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Fa;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\FaWiersz;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Naglowek;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\NIPGroup;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\P_13_1Group;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Podmiot1;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Podmiot1DaneIdentyfikacyjne;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Podmiot2;
use Rcalicdan\KSEFClient\DTOs\Requests\Sessions\Podmiot2DaneIdentyfikacyjne;
use Rcalicdan\KSEFClient\ValueObjects\NIP;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\AdresL1;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\KodKraju;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\KodWaluty;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\Nazwa;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\NrWierszaFa;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_1;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_11;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_12;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_13_1;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_14_1;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_15;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_17;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_2;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_7;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_8A;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_8B;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\P_9A;

class KsefInvoiceMapper
{
    public function mapToFaktura(Invoice $invoice): Faktura
    {
        $invoice->loadMissing(['client', 'pickup.wasteType']);
        $selfBilling = config('ksef.self_billing', true);

        $seller = $selfBilling ? $this->getRestaurantData($invoice) : $this->getOlejosData();
        $buyer = $selfBilling ? $this->getOlejosData() : $this->getRestaurantData($invoice);

        return new Faktura(
            naglowek: new Naglowek(), 
            podmiot1: $this->createPodmiot1($seller), 
            podmiot2: $this->createPodmiot2($buyer), 
            fa: $this->buildInvoiceDetails($invoice, $selfBilling)
        );
    }

    private function getRestaurantData(Invoice $invoice): array
    {
        return [
            'nip'   => new NIP(preg_replace('/[^0-9]/', '', $invoice->client->vat_id)),
            'nazwa' => new Nazwa($invoice->client->company_name),
            'adres' => new Adres(new AdresL1($invoice->client->full_address), new KodKraju('PL'))
        ];
    }

    private function getOlejosData(): array
    {
        return [
            'nip'   => new NIP(preg_replace('/[^0-9]/', '', config('ksef.issuer_nip'))),
            'nazwa' => new Nazwa(config('company.name')),
            'adres' => new Adres(new AdresL1(config('company.address')), new KodKraju('PL'))
        ];
    }

    private function createPodmiot1(array $data): Podmiot1
    {
        return new Podmiot1(
            daneIdentyfikacyjne: new Podmiot1DaneIdentyfikacyjne($data['nip'], $data['nazwa']),
            adres: $data['adres']
        );
    }

    private function createPodmiot2(array $data): Podmiot2
    {
        return new Podmiot2(
            daneIdentyfikacyjne: new Podmiot2DaneIdentyfikacyjne(
                idGroup: new NIPGroup($data['nip']),
                nazwa: $data['nazwa']
            ),
            adres: $data['adres']
        );
    }

    private function buildInvoiceDetails(Invoice $invoice, bool $isSelfBilling): Fa
    {
        $pickup = $invoice->pickup;
        
        $faWiersz = new FaWiersz(
            nrWierszaFa: new NrWierszaFa(1),
            p_7: new P_7('Skup olejów: ' . ($pickup->wasteType->name ?? 'Zalecane')), 
            p_8A: new P_8A('kg'),
            p_8B: new P_8B((float) $pickup->waste_quantity),
            p_9A: new P_9A((float) $pickup->applied_price_rate),
            p_11: new P_11((float) $invoice->net_amount), 
            p_12: P_12::Tax23 
        );

        $adnotacje = new Adnotacje(
            p_17: $isSelfBilling ? P_17::Samofakturowanie : P_17::Default
        );

        return new Fa(
            kodWaluty: new KodWaluty($invoice->client->currency ?? 'PLN'),
            p_1: new P_1($invoice->issue_date), 
            p_2: new P_2($invoice->invoice_number), 
            p_15: new P_15((float) $invoice->gross_amount), 
            p_13_1Group: new P_13_1Group(
                p_13_1: new P_13_1((float) $invoice->net_amount), 
                p_14_1: new P_14_1((float) $invoice->vat_amount)
            ),
            adnotacje: $adnotacje, 
            faWiersz: [$faWiersz]
        );
    }
}