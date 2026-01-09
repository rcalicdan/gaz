<?php

namespace Database\Factories;

use App\Models\Pickup;
use App\Models\PickupBox;
use Illuminate\Database\Eloquent\Factories\Factory;

class PickupBoxFactory extends Factory
{
    protected $model = PickupBox::class;

    public function definition(): array
    {
        return [
            'pickup_id' => Pickup::factory(),
            'box_number' => fake()->unique()->numerify('BOX-####'),
            'note' => fake()->optional()->sentence(),
        ];
    }
}