<?php

namespace App\Livewire\Pickups;

use App\Models\Pickup;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ViewPage extends Component
{
    public Pickup $pickup;

    public function mount(Pickup $pickup)
    {
        $this->authorize('view', $pickup);
        $this->pickup = $pickup->load([
            'client',
            'driver.user',
            'wasteType',
            'route',
            'boxes',
            'invoice',
            'kpoDocument'
        ]);
    }

    public function render()
    {
        return view('livewire.pickups.view-page');
    }
}