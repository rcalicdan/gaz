<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PriceList;
use App\Models\WasteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $cities = [
            ['city' => 'Quezon City', 'province' => 'Metro Manila', 'lat' => 14.6760, 'lng' => 121.0437],
            ['city' => 'Manila', 'province' => 'Metro Manila', 'lat' => 14.5995, 'lng' => 120.9842],
            ['city' => 'Makati', 'province' => 'Metro Manila', 'lat' => 14.5547, 'lng' => 121.0244],
            ['city' => 'Pasig', 'province' => 'Metro Manila', 'lat' => 14.5764, 'lng' => 121.0851],
        ];

        $location = fake()->randomElement($cities);

        return [
            'company_name' => fake()->company(),
            'vat_id' => fake()->numerify('PL##########'), 
            'street_name' => fake()->streetName(),
            'street_number' => fake()->buildingNumber(),
            'city' => $location['city'],
            'zip_code' => fake()->postcode(),
            'province' => $location['province'],
            'latitude' => $location['lat'] + fake()->randomFloat(4, -0.05, 0.05),
            'longitude' => $location['lng'] + fake()->randomFloat(4, -0.05, 0.05),
            'contact_person' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'phone_number' => fake()->phoneNumber(),
            'brand_category' => fake()->optional()->randomElement(['Restaurant', 'Retail', 'Office', 'Manufacturing']),
            'default_waste_type_id' => WasteType::factory(),
            'price_list_id' => PriceList::factory(),
            'pickup_frequency_days' => fake()->randomElement([7, 14, 30]),
            'price_rate' => fake()->randomFloat(2, 50, 300),
            'currency' => 'PLN',
            'tax_rate' => fake()->randomElement([0, 8, 23]),
            'auto_invoice' => fake()->boolean(70),
            'auto_kpo' => fake()->boolean(70),
            'last_contact_date' => fake()->optional()->dateTimeBetween('-6 months', 'now'),
            'last_pickup_date' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'pickup_frequency' => fake()->randomElement(\App\Enums\PickupFrequency::cases()),
        ];
    }

    public function withCoordinates(): static
    {
        return $this->state(fn (array $attributes) => [
            'latitude' => fake()->latitude(14.4, 14.8),
            'longitude' => fake()->longitude(120.9, 121.2),
        ]);
    }

    public function withoutCoordinates(): static
    {
        return $this->state(fn (array $attributes) => [
            'latitude' => null,
            'longitude' => null,
        ]);
    }
}