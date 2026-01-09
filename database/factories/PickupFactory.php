<?php

namespace Database\Factories;

use App\Enums\PickupStatus;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Pickup;
use App\Models\Route;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PickupFactory extends Factory
{
    protected $model = Pickup::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'route_id' => Route::factory(),
            'assigned_driver_id' => Driver::factory(),
            'sequence_order' => fake()->numberBetween(1, 20),
            'scheduled_date' => fake()->dateTimeBetween('-1 week', '+2 weeks'),
            'actual_pickup_time' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
            'status' => fake()->randomElement(PickupStatus::cases()),
            'waste_quantity' => fake()->randomFloat(2, 10, 500),
            'waste_type_id' => WasteType::factory(),
            'driver_note' => fake()->optional()->sentence(),
            'applied_price_rate' => fake()->randomFloat(2, 50, 300),
            'certificate_number' => fake()->optional()->numerify('CERT-####-####'),
        ];
    }
}