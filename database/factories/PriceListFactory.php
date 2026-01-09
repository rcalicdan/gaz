<?php

namespace Database\Factories;

use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceListFactory extends Factory
{
    protected $model = PriceList::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true) . ' Price List',
            'description' => fake()->optional()->sentence(),
            'is_active' => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}