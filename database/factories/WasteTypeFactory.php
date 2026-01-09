<?php

namespace Database\Factories;

use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteTypeFactory extends Factory
{
    protected $model = WasteType::class;

    public function definition(): array
    {
        $wasteTypes = [
            ['code' => '150101', 'name' => 'Paper and cardboard packaging'],
            ['code' => '150102', 'name' => 'Plastic packaging'],
            ['code' => '150103', 'name' => 'Wooden packaging'],
            ['code' => '200101', 'name' => 'Paper and cardboard'],
            ['code' => '200139', 'name' => 'Plastics'],
            ['code' => '170201', 'name' => 'Wood'],
            ['code' => '200140', 'name' => 'Metal'],
            ['code' => '200102', 'name' => 'Glass'],
        ];

        $type = fake()->randomElement($wasteTypes);

        return [
            'code' => $type['code'],
            'name' => $type['name'],
            'description' => fake()->optional()->sentence(),
        ];
    }
}