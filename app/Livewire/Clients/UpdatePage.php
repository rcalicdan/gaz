<?php

namespace App\Livewire\Clients;

use App\Services\ClientService;
use App\Models\Client;
use App\Models\WasteType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdatePage extends Component
{
    public Client $client;
    public $company_name = '';
    public $vat_id = '';
    public $street_name = '';
    public $street_number = '';
    public $city = '';
    public $zip_code = '';
    public $province = '';
    public $email = '';
    public $phone_number = '';
    public $brand_category = '';
    public $default_waste_type_id = '';
    public $price_rate = '';
    public $tax_rate = '';
    public $auto_invoice = false;
    public $auto_kpo = false;

    protected ClientService $clientService;

    public function boot(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->company_name = $client->company_name;
        $this->vat_id = $client->vat_id;
        $this->street_name = $client->street_name;
        $this->street_number = $client->street_number;
        $this->city = $client->city;
        $this->zip_code = $client->zip_code;
        $this->province = $client->province;
        $this->email = $client->email;
        $this->phone_number = $client->phone_number;
        $this->brand_category = $client->brand_category;
        $this->default_waste_type_id = $client->default_waste_type_id;
        $this->price_rate = $client->price_rate;
        $this->tax_rate = $client->tax_rate;
        $this->auto_invoice = $client->auto_invoice;
        $this->auto_kpo = $client->auto_kpo;
    }

    public function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'vat_id' => ['nullable', 'string', 'max:50', Rule::unique('clients')->ignore($this->client->id)],
            'street_name' => 'required|string|max:255',
            'street_number' => 'nullable|string|max:50',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'province' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('clients')->ignore($this->client->id)],
            'phone_number' => 'required|string|max:50',
            'brand_category' => 'nullable|string|max:255',
            'default_waste_type_id' => 'nullable|exists:waste_types,id',
            'price_rate' => 'nullable|numeric|min:0|max:999999.99',
            'tax_rate' => 'nullable|integer|min:0|max:100',
            'auto_invoice' => 'boolean',
            'auto_kpo' => 'boolean',
        ];
    }

    public function validationAttributes()
    {
        return [
            'company_name' => 'company name',
            'vat_id' => 'VAT ID',
            'street_name' => 'street name',
            'street_number' => 'street number',
            'city' => 'city',
            'zip_code' => 'zip code',
            'province' => 'province',
            'email' => 'email',
            'phone_number' => 'phone number',
            'brand_category' => 'brand category',
            'default_waste_type_id' => 'default waste type',
            'price_rate' => 'price rate',
            'tax_rate' => 'tax rate',
            'auto_invoice' => 'auto invoice',
            'auto_kpo' => 'auto KPO',
        ];
    }

    public function update()
    {
        $this->authorize('update', $this->client);
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
            'phone_number' => $this->phone_number,
            'brand_category' => $this->brand_category,
            'default_waste_type_id' => $this->default_waste_type_id ?: null,
            'price_rate' => $this->price_rate ?: null,
            'tax_rate' => $this->tax_rate ?: null,
            'auto_invoice' => $this->auto_invoice,
            'auto_kpo' => $this->auto_kpo,
        ];

        try {
            $this->clientService->updateClientInformation($this->client, $data);

            session()->flash('success', __('Client has been successfully updated!'));

            return redirect()->route('clients.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while updating the client. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('update', $this->client);
        $wasteTypes = WasteType::orderBy('name')->get();

        return view('livewire.clients.update-page', [
            'wasteTypes' => $wasteTypes
        ]);
    }
}
