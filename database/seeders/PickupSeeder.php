<?php

namespace Database\Seeders;

use App\Enums\PickupStatus;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Pickup;
use App\Models\Route;
use App\Models\WasteType;
use Illuminate\Database\Seeder;

class PickupSeeder extends Seeder
{
    public function run(): void
    {
        $routes = Route::all();
        $clients = Client::withCoordinates()->get();
        $drivers = Driver::all();
        $wasteTypes = WasteType::all();

        if ($routes->isEmpty() || $clients->isEmpty() || $drivers->isEmpty() || $wasteTypes->isEmpty()) {
            $this->command->warn('Missing required data. Run other seeders first.');
            return;
        }

        foreach ($routes as $route) {
            $pickupCount = rand(3, 8);
            $routeClients = $clients->random(min($pickupCount, $clients->count()));

            foreach ($routeClients as $index => $client) {
                Pickup::create([
                    'client_id' => $client->id,
                    'route_id' => $route->id,
                    'assigned_driver_id' => $drivers->random()->id,
                    'sequence_order' => $index + 1,
                    'scheduled_date' => $route->date,
                    'actual_pickup_time' => $route->status === 'completed' ? now() : null,
                    'status' => $route->status === 'completed' ? PickupStatus::COMPLETED : PickupStatus::SCHEDULED,
                    'waste_quantity' => rand(50, 300),
                    'waste_type_id' => $wasteTypes->random()->id,
                    'driver_note' => null,
                    'applied_price_rate' => $client->price_rate,
                    'certificate_number' => $route->status === 'completed' ? 'CERT-' . now()->format('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT) : null,
                ]);
            }
        }
    }
}