<?php

namespace Database\Factories;

use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceListItemFactory extends Factory
{
    protected $model = PriceListItem::class;

    public function definition(): array
    {
        return [
            'price_list_id' => PriceList::factory(),
            'waste_type_id' => WasteType::factory(),
            'base_price' => fake()->randomFloat(2, 10, 500),
            'currency' => 'PLN',
            'tax_rate' => fake()->randomElement([0, 0.07, 0.08, 0.23]), 
            'unit_type' => fake()->randomElement(['per_pickup', 'per_kg', 'per_ton', 'per_box']),
            'min_quantity' => fake()->optional()->randomFloat(2, 0, 100),
            'max_quantity' => fake()->optional()->randomFloat(2, 100, 1000),
        ];
    }
}