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
        $this->client = $client;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;

        if ($tab === 'address') {
            // Trigger Livewire-dispatched events so the frontend can react to tab activation
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
                'lat' => $this->client->latitude,
                'lng' => $this->client->longitude
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
