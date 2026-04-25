<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Pickup;
use App\Services\KsefInvoiceMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Rcalicdan\KSEFClient\Validator\Rules\Xml\SchemaRule;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use Rcalicdan\KSEFClient\ValueObjects\SchemaPath;

uses(RefreshDatabase::class);

it('validates that the invoice uses self-billing and has the correct P_17 flag', function () {
    config([
        'company.name' => 'Olejos Logistics',
        'company.nip' => '1111111111',
        'company.address' => 'ul. Przemysłowa 5, Kielce',
    ]);

    $client = Client::factory()->create([
        'company_name' => 'Restaurant ABC',
        'vat_id' => '9999999999',
    ]);

    $pickup = Pickup::factory()->create(['client_id' => $client->id]);
    
    $invoice = Invoice::factory()->create([
        'pickup_id' => $pickup->id,
        'client_id' => $client->id,
        'net_amount' => 100.00,
        'vat_amount' => 23.00,
        'gross_amount' => 123.00
    ]);

    $mapper = new KsefInvoiceMapper();
    $faktura = $mapper->mapToFaktura($invoice);

    expect($faktura->podmiot1->daneIdentyfikacyjne->nip->value)->toBe('9999999999') 
        ->and($faktura->podmiot2->daneIdentyfikacyjne->idGroup->nip->value)->toBe('1111111111') 
        ->and($faktura->fa->adnotacje->p_17->value)->toBe('1'); 

    $xml = $faktura->toXml();
    $schemaRule = new SchemaRule(SchemaPath::from(FormCode::Fa3->getSchemaPath()));
    
    expect(fn() => $schemaRule->handle($xml))->not->toThrow(Exception::class);
});