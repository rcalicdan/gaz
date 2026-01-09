<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\RouteOptimization;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteOptimizationFactory extends Factory
{
    protected $model = RouteOptimization::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'optimization_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'optimization_result' => [
                'total_distance' => fake()->numberBetween(50, 300),
                'total_duration' => fake()->numberBetween(180, 480),
                'routes' => [],
            ],
            'pickup_sequence' => fake()->numberBetween(1, 100, 10),
            'total_distance' => fake()->randomFloat(2, 50, 300),
            'total_time' => fake()->numberBetween(180, 480),
            'is_manual_edit' => fake()->boolean(20),
            'manual_modifications' => fake()->optional()->passthrough([
                'modified_at' => now()->toDateTimeString(),
                'changes' => 'Reordered pickups',
            ]),
            'requires_optimization' => fake()->boolean(30),
        ];
    }
}