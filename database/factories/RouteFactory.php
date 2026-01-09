<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition(): array
    {
        return [
            'driver_id' => User::factory(),
            'date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'name' => fake()->words(3, true) . ' Route',
            'status' => fake()->randomElement(['planned', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}