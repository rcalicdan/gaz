<?php

namespace App\Livewire\Pickups;

use App\Enums\PickupStatus;
use App\Models\Pickup;
use App\Services\PickupService;
use Livewire\Component;

class CreatePage extends Component
{
    public $client_id = null;
    public $assigned_driver_id = null;
    public $waste_type_id = null;
    public $scheduled_date = '';
    public $status = 'scheduled';
    public $waste_quantity = '';
    public $applied_price_rate = '';
    public $driver_note = '';
    public $certificate_number = '';
    public $sequence_order = '';
    public $boxes = [];

    protected PickupService $pickupService;
    protected $listeners = ['itemSelected'];

    public function boot(PickupService $pickupService)
    {
        $this->pickupService = $pickupService;
    }

    public function mount()
    {
        $this->scheduled_date = now()->format('Y-m-d');
        $this->status = PickupStatus::SCHEDULED->value;
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
            'sequence_order' => 'nullable|integer|min:0',
            'boxes' => 'nullable|array',
            'boxes.*.box_number' => 'required|string|max:255',
            'boxes.*.note' => 'nullable|string|max:500',
        ];
    }

    public function validationAttributes()
    {
        $attributes = [
            'client_id' => 'client',
            'assigned_driver_id' => 'driver',
            'waste_type_id' => 'waste type',
            'scheduled_date' => 'scheduled date',
            'status' => 'status',
            'waste_quantity' => 'waste quantity',
            'applied_price_rate' => 'applied price rate',
            'driver_note' => 'driver note',
            'certificate_number' => 'certificate number',
            'sequence_order' => 'sequence order',
        ];

        foreach ($this->boxes as $index => $box) {
            $attributes["boxes.{$index}.box_number"] = "box number #" . ($index + 1);
            $attributes["boxes.{$index}.note"] = "note #" . ($index + 1);
        }

        return $attributes;
    }

    public function addBox()
    {
        $this->boxes[] = [
            'box_number' => '',
            'note' => '',
        ];
    }

    public function removeBox($index)
    {
        unset($this->boxes[$index]);
        $this->boxes = array_values($this->boxes);
    }

    public function save()
    {
        $this->authorize('create', Pickup::class);
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
            'sequence_order' => $this->sequence_order ?: null,
            'boxes' => $this->boxes,
        ];

        try {
            $pickup = $this->pickupService->createPickup($data);
            session()->flash('success', __('Pickup has been successfully created!'));
            return redirect()->route('pickups.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while creating the pickup. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('create', Pickup::class);
        return view('livewire.pickups.create-page');
    }
}