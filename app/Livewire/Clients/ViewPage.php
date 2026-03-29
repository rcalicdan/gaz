<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class ViewPage extends Component
{
    public Client $client;
    public $activeTab = 'overview';

    public function mount(Client $client)
    {
        $this->client = $client->load([
            'phoneNumbers',
            'primaryPhoneNumber',
            'defaultWasteType',
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;

        if ($tab === 'address') {
            $this->dispatch('init-map');
            $this->dispatch('invalidate-map-size');
        }
    }

    public function manualGeocode()
    {
        $success = $this->client->forceGeocode();

        $this->client->refresh();

        if ($success) {
            $this->dispatch('show-message', [
                'type' => 'success',
                'message' => __('Address successfully geocoded!')
            ]);

            $this->dispatch('update-map-coordinates', [
                'lat' => $this->client->premises_latitude,
                'lng' => $this->client->premises_longitude
            ]);
        } else {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => __('Could not find coordinates for this address.')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.clients.view-page');
    }
}