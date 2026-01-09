<?php

namespace App\Livewire\Boxes;

use App\Models\Pickup;
use App\Models\PickupBox;
use Livewire\Component;

class CreatePage extends Component
{
    public Pickup $pickup;
    public $box_number = '';
    public $note = '';

    public function mount(Pickup $pickup)
    {
        $this->pickup = $pickup;
    }

    public function rules()
    {
        return [
            'box_number' => 'required|string|max:255',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            PickupBox::create([
                'pickup_id' => $this->pickup->id,
                'box_number' => $this->box_number,
                'note' => $this->note ?: null,
            ]);

            session()->flash('success', __('Box added successfully!'));

            return redirect()->route('pickups.view', $this->pickup->id);
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to add box. Please try again.'));
        }
    }

    public function render()
    {
        return view('livewire.boxes.create-page');
    }
}