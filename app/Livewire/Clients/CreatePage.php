<?php

namespace App\Livewire\Clients;

use App\Services\ClientService;
use App\Models\Client;
use App\Models\WasteType;
use Livewire\Component;

class CreatePage extends Component
{
    public $company_name = '';
    public $vat_id = '';
    public $street_name = '';
    public $street_number = '';
    public $city = '';
    public $zip_code = '';
    public $province = '';
    public $email = '';
    public $brand_category = '';
    public $default_waste_type_id = '';
    public $price_rate = '';
    public $tax_rate = '';
    public $auto_invoice = false;
    public $auto_kpo = false;
    public $phoneNumbers = [];

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

    public function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'vat_id' => 'nullable|string|max:50|unique:clients,vat_id',
            'street_name' => 'required|string|max:255',
            'street_number' => 'nullable|string|max:50',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'province' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email',
            'brand_category' => 'nullable|string|max:255',
            'default_waste_type_id' => 'nullable|exists:waste_types,id',
            'price_rate' => 'nullable|numeric|min:0|max:999999.99',
            'tax_rate' => 'nullable|integer|min:0|max:100',
            'auto_invoice' => 'boolean',
            'auto_kpo' => 'boolean',
            'phoneNumbers.*.phone_number' => 'required|string|max:50',
            'phoneNumbers.*.label' => 'nullable|string|max:100',
            'phoneNumbers.*.is_primary' => 'boolean',
        ];
    }

    public function validationAttributes()
    {
        $attributes = [
            'company_name' => 'company name',
            'vat_id' => 'VAT ID',
            'street_name' => 'street name',
            'street_number' => 'street number',
            'city' => 'city',
            'zip_code' => 'zip code',
            'province' => 'province',
            'email' => 'email',
            'brand_category' => 'brand category',
            'default_waste_type_id' => 'default waste type',
            'price_rate' => 'price rate',
            'tax_rate' => 'tax rate',
            'auto_invoice' => 'auto invoice',
            'auto_kpo' => 'auto KPO',
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
            'street_name' => $this->street_name,
            'street_number' => $this->street_number,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'province' => $this->province,
            'email' => $this->email,
            'brand_category' => $this->brand_category,
            'default_waste_type_id' => $this->default_waste_type_id ?: null,
            'price_rate' => $this->price_rate ?: null,
            'tax_rate' => $this->tax_rate ?: null,
            'auto_invoice' => $this->auto_invoice,
            'auto_kpo' => $this->auto_kpo,
        ];

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