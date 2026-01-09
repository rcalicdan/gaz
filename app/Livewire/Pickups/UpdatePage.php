<?php

namespace App\Livewire\Pickups;

use App\Enums\PickupStatus;
use App\Models\Pickup;
use App\Services\PickupService;
use Livewire\Component;

class UpdatePage extends Component
{
    public Pickup $pickup;
    public $client_id = null;
    public $assigned_driver_id = null;
    public $waste_type_id = null;
    public $scheduled_date = '';
    public $status = 'scheduled';
    public $waste_quantity = '';
    public $applied_price_rate = '';
    public $driver_note = '';
    public $certificate_number = '';
    public $actual_pickup_time = '';
    public $sequence_order = '';

    protected PickupService $pickupService;
    protected $listeners = ['itemSelected'];

    public function boot(PickupService $pickupService)
    {
        $this->pickupService = $pickupService;
    }

    public function mount(Pickup $pickup)
    {
        $this->pickup = $pickup->load(['client', 'driver', 'wasteType']);
        $this->client_id = $pickup->client_id;
        $this->assigned_driver_id = $pickup->assigned_driver_id;
        $this->waste_type_id = $pickup->waste_type_id;
        $this->scheduled_date = $pickup->scheduled_date->format('Y-m-d');
        $this->status = $pickup->status->value;
        $this->waste_quantity = $pickup->waste_quantity;
        $this->applied_price_rate = $pickup->applied_price_rate;
        $this->driver_note = $pickup->driver_note;
        $this->certificate_number = $pickup->certificate_number;
        $this->actual_pickup_time = $pickup->actual_pickup_time?->format('Y-m-d\TH:i');
        $this->sequence_order = $pickup->sequence_order;
    }

    public function itemSelected($data)
    {
        $this->{$data['name']} = $data['value'];
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'assigned_driver_id' => 'nullable|exists:drivers,id',
            'waste_type_id' => 'required|exists:waste_types,id',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:' . implode(',', PickupStatus::values()),
            'waste_quantity' => 'nullable|numeric|min:0|max:999999.99',
            'applied_price_rate' => 'nullable|numeric|min:0|max:999999.99',
            'driver_note' => 'nullable|string|max:1000',
            'certificate_number' => 'nullable|string|max:255',
            'actual_pickup_time' => 'nullable|date',
            'sequence_order' => 'nullable|integer|min:0',
        ];
    }

    public function validationAttributes()
    {
        return [
            'client_id' => 'client',
            'assigned_driver_id' => 'driver',
            'waste_type_id' => 'waste type',
            'scheduled_date' => 'scheduled date',
            'status' => 'status',
            'waste_quantity' => 'waste quantity',
            'applied_price_rate' => 'applied price rate',
            'driver_note' => 'driver note',
            'certificate_number' => 'certificate number',
            'actual_pickup_time' => 'actual pickup time',
            'sequence_order' => 'sequence order',
        ];
    }

    public function update()
    {
        $this->authorize('update', $this->pickup);
        $this->validate();

        $data = [
            'client_id' => $this->client_id,
            'assigned_driver_id' => $this->assigned_driver_id,
            'waste_type_id' => $this->waste_type_id,
            'scheduled_date' => $this->scheduled_date,
            'status' => $this->status,
            'waste_quantity' => $this->waste_quantity ?: null,
            'applied_price_rate' => $this->applied_price_rate ?: null,
            'driver_note' => $this->driver_note ?: null,
            'certificate_number' => $this->certificate_number ?: null,
            'actual_pickup_time' => $this->actual_pickup_time ?: null,
            'sequence_order' => $this->sequence_order ?: null,
        ];

        try {
            $this->pickupService->updatePickup($this->pickup->id, $data);
            session()->flash('success', __('Pickup has been successfully updated!'));
            return redirect()->route('pickups.view', $this->pickup->id);
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while updating the pickup. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('update', $this->pickup);
        return view('livewire.pickups.update-page');
    }
}