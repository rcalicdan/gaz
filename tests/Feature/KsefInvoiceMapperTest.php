<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Pickup;
use App\Models\WasteType;
use App\Services\KsefInvoiceMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Rcalicdan\KSEFClient\Validator\Rules\Xml\SchemaRule;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;
use Rcalicdan\KSEFClient\Exceptions\XmlValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'company.name' => 'Olejos Sp. z o.o.',
        'company.nip' => '1111111111',
        'company.address' => 'ul. Przykładowa 1, 00-001 Warszawa',
    ]);
});

it('maps laravel invoice to valid ksef faktura xml', function () {
    $wasteType = WasteType::create([
        'code' => '20 01 08',
        'name' => 'Odpady kuchenne ulegające biodegradacji',
    ]);

    $client = Client::create([
        'company_name' => 'Testowa Restauracja Sp. z o.o.',
        'vat_id' => '2222222222', 
        'registered_street_name' => 'ul. Smaczna',
        'registered_street_number' => '15',
        'registered_city' => 'Kraków',
        'registered_zip_code' => '30-001',
        'email' => 'kontakt@restauracja.pl',
        'pickup_frequency' => 'weekly',
    ]);

    $pickup = Pickup::create([
        'client_id' => $client->id,
        'waste_type_id' => $wasteType->id,
        'scheduled_date' => now(),
        'status' => 'completed',
        'waste_quantity' => 150.50, 
        'applied_price_rate' => 2.00, 
    ]);

    $netAmount = 301.00;
    $vatAmount = round($netAmount * 0.23, 2); 
    $grossAmount = $netAmount + $vatAmount;   

    $invoice = Invoice::create([
        'pickup_id' => $pickup->id,
        'client_id' => $client->id,
        'invoice_number' => 'FV/2026/03/001',
        'issue_date' => now(),
        'due_date' => now()->addDays(14),
        'net_amount' => $netAmount,
        'vat_amount' => $vatAmount,
        'gross_amount' => $grossAmount,
        'ksef_status' => 'pending',
    ]);

    $mapper = new KsefInvoiceMapper();
    $fakturaDto = $mapper->mapToFaktura($invoice);

    expect($fakturaDto->podmiot1->daneIdentyfikacyjne->nip->value)->toBe('1111111111')
        ->and($fakturaDto->podmiot2->daneIdentyfikacyjne->idGroup->nip->value)->toBe('2222222222')
        ->and($fakturaDto->fa->p_15->value)->toBe('370.23')
        ->and($fakturaDto->fa->faWiersz[0]->p_12->value)->toBe('23');

    $xml = $fakturaDto->toXml();
    
    expect($xml)
        ->toContain('<Fa>')
        ->toContain('<NIP>2222222222</NIP>')
        ->toContain('<P_15>370.23</P_15>');

    $schemaPath = SchemaPath::from(FormCode::Fa3->getSchemaPath());
    $schemaRule = new SchemaRule($schemaPath);
    
    expect(fn() => $schemaRule->handle($xml))->not->toThrow(XmlValidationException::class);
});