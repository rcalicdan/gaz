<?php

namespace App\Livewire\Boxes;

use App\Models\PickupBox;
use Livewire\Component;

class ViewPage extends Component
{
    public PickupBox $pickupBox;

    public function mount(PickupBox $pickupBox)
    {
        $this->pickupBox = $pickupBox;
    }

    public function render()
    {
        return view('livewire.boxes.view-page', [
            'pickupBox' => $this->pickupBox,
        ]);
    }
}
