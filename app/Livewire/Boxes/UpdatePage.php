<?php

namespace App\Livewire\Boxes;

use App\Models\PickupBox;
use Livewire\Component;

class UpdatePage extends Component
{
    public PickupBox $pickupBox;
    public $box_number = '';
    public $note = '';

    public function mount(PickupBox $pickupBox)
    {
        $this->pickupBox = $pickupBox;
        $this->box_number = $pickupBox->box_number;
        $this->note = $pickupBox->note;
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
            $this->pickupBox->update([
                'box_number' => $this->box_number,
                'note' => $this->note ?: null,
            ]);

            session()->flash('success', __('Box updated successfully!'));

            return redirect()->route('pickups.view', $this->pickupBox->pickup_id);
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to update box. Please try again.'));
        }
    }

    public function render()
    {
        return view('livewire.boxes.update-page');
    }
}