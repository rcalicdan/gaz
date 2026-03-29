<?php

namespace App\Livewire\Clients;

use App\Services\ClientService;
use App\Models\Client;
use App\Models\WasteType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePage extends Component
{
    public $company_name = '';
    public $vat_id = '';
    public $registered_street_name = '';
    public $registered_street_number = '';
    public $registered_city = '';
    public $registered_zip_code = '';
    public $registered_province = '';
    public $has_separate_premises = false;
    public $premises_street_name = '';
    public $premises_street_number = '';
    public $premises_city = '';
    public $premises_zip_code = '';
    public $premises_province = '';
    public $email = '';
    public $contact_person = '';
    public $brand_category = '';
    public $default_waste_type_id = '';
    public $price_rate = '';
    public $tax_rate = '';
    public $auto_invoice = false;
    public $auto_kpo = false;
    public $pickup_frequency = null;
    public $custom_pickup_days = null;
    public $phoneNumbers = [];
    public $contract_number = '';
    public $contract_signed_date = '';
    public $declaration_expiry_date = '';

    protected ClientService $clientService;

    public function boot(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function mount()
    {
        $this->phoneNumbers = [
            ['phone_number' => '', 'label' => '', 'is_primary' => true]
        ];
    }

    public function addPhoneNumber()
    {
        $this->phoneNumbers[] = ['phone_number' => '', 'label' => '', 'is_primary' => false];
    }

    public function removePhoneNumber($index)
    {
        if (count($this->phoneNumbers) > 1) {
            unset($this->phoneNumbers[$index]);
            $this->phoneNumbers = array_values($this->phoneNumbers);

            if (!collect($this->phoneNumbers)->contains('is_primary', true) && count($this->phoneNumbers) > 0) {
                $this->phoneNumbers[0]['is_primary'] = true;
            }
        }
    }

    public function setPrimary($index)
    {
        foreach ($this->phoneNumbers as $key => $phone) {
            $this->phoneNumbers[$key]['is_primary'] = false;
        }

        $this->phoneNumbers[$index]['is_primary'] = true;
    }

    public function updatedHasSeparatePremises($value)
    {
        if (!$value) {
            $this->premises_street_name = '';
            $this->premises_street_number = '';
            $this->premises_city = '';
            $this->premises_zip_code = '';
            $this->premises_province = '';
        }
    }

    public function rules()
    {
        $rules = [
            'company_name' => 'required|string|max:255',
            'vat_id' => 'nullable|string|max:50|unique:clients,vat_id',
            'contract_number' => 'nullable|string|max:255|unique:clients,contract_number',
            'contract_signed_date' => 'nullable|date',
            'declaration_expiry_date' => 'nullable|date',
            'registered_street_name' => 'required|string|max:255',
            'registered_street_number' => 'nullable|string|max:50',
            'registered_city' => 'required|string|max:255',
            'registered_zip_code' => 'required|string|max:20',
            'registered_province' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email',
            'contact_person' => 'nullable|string|max:255',
            'brand_category' => 'nullable|string|max:255',
            'default_waste_type_id' => 'nullable|exists:waste_types,id',
            'price_rate' => 'nullable|numeric|min:0|max:999999.99',
            'tax_rate' => 'nullable|integer|min:0|max:100',
            'auto_invoice' => 'boolean',
            'auto_kpo' => 'boolean',
            'pickup_frequency' => 'required',
            'custom_pickup_days' => [
                Rule::requiredIf($this->pickup_frequency === 'custom'),
                'nullable',
                'integer',
                'min:1',
                'max:365'
            ],
            'phoneNumbers.*.phone_number' => 'required|string|max:50',
            'phoneNumbers.*.label' => 'nullable|string|max:100',
            'phoneNumbers.*.is_primary' => 'boolean',
        ];

        if ($this->has_separate_premises) {
            $rules['premises_street_name'] = 'required|string|max:255';
            $rules['premises_street_number'] = 'nullable|string|max:50';
            $rules['premises_city'] = 'required|string|max:255';
            $rules['premises_zip_code'] = 'required|string|max:20';
            $rules['premises_province'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function validationAttributes()
    {
        $attributes = [
            'company_name' => 'company name',
            'vat_id' => 'VAT ID',
            'registered_street_name' => 'registered street name',
            'registered_street_number' => 'registered street number',
            'registered_city' => 'registered city',
            'registered_zip_code' => 'registered zip code',
            'registered_province' => 'registered province',
            'premises_street_name' => 'premises street name',
            'premises_street_number' => 'premises street number',
            'premises_city' => 'premises city',
            'premises_zip_code' => 'premises zip code',
            'premises_province' => 'premises province',
            'email' => 'email',
            'contact_person' => 'contact person',
            'contract_number' => 'contract number',
            'contract_signed_date' => 'contract signed date',
            'declaration_expiry_date' => 'declaration expiry date',
            'brand_category' => 'brand category',
            'default_waste_type_id' => 'default waste type',
            'price_rate' => 'price rate',
            'tax_rate' => 'tax rate',
            'auto_invoice' => 'auto invoice',
            'auto_kpo' => 'auto KPO',
            'pickup_frequency' => 'pickup frequency',
            'custom_pickup_days' => 'custom pickup days',
        ];

        foreach ($this->phoneNumbers as $index => $phone) {
            $attributes["phoneNumbers.{$index}.phone_number"] = 'phone number';
            $attributes["phoneNumbers.{$index}.label"] = 'label';
        }

        return $attributes;
    }

    public function save()
    {
        $this->authorize('create', Client::class);
        $this->validate();

        $data = [
            'company_name' => $this->company_name,
            'vat_id' => $this->vat_id,
            'contract_number' => $this->contract_number,
            'contract_signed_date' => $this->contract_signed_date ?: null,
            'declaration_expiry_date' => $this->declaration_expiry_date ?: null,
            'registered_street_name' => $this->registered_street_name,
            'registered_street_number' => $this->registered_street_number,
            'registered_city' => $this->registered_city,
            'registered_zip_code' => $this->registered_zip_code,
            'registered_province' => $this->registered_province,
            'email' => $this->email,
            'contact_person' => $this->contact_person,
            'brand_category' => $this->brand_category,
            'default_waste_type_id' => $this->default_waste_type_id ?: null,
            'price_rate' => $this->price_rate ?: null,
            'tax_rate' => $this->tax_rate ?: null,
            'auto_invoice' => $this->auto_invoice,
            'auto_kpo' => $this->auto_kpo,
            'pickup_frequency' => $this->pickup_frequency,
            'custom_pickup_days' => $this->pickup_frequency === 'custom'
                ? $this->custom_pickup_days
                : null,
        ];

        if ($this->has_separate_premises) {
            $data['premises_street_name'] = $this->premises_street_name;
            $data['premises_street_number'] = $this->premises_street_number;
            $data['premises_city'] = $this->premises_city;
            $data['premises_zip_code'] = $this->premises_zip_code;
            $data['premises_province'] = $this->premises_province;
        }

        try {
            $client = $this->clientService->storeNewClient($data);

            foreach ($this->phoneNumbers as $phoneData) {
                if (!empty($phoneData['phone_number'])) {
                    $client->phoneNumbers()->create([
                        'phone_number' => $phoneData['phone_number'],
                        'label' => $phoneData['label'],
                        'is_primary' => $phoneData['is_primary'],
                    ]);
                }
            }

            $client->geocodeAddress();

            session()->flash('success', __('Client has been successfully created!'));

            return redirect()->route('clients.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while creating the client. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('create', Client::class);
        $wasteTypes = WasteType::orderBy('name')->get();

        return view('livewire.clients.create-page', [
            'wasteTypes' => $wasteTypes
        ]);
    }
}