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
        $locations = [
            ['city' => 'Warszawa', 'province' => 'Mazowieckie', 'lat' => 52.2302, 'lng' => 21.0032], 
            ['city' => 'Warszawa', 'province' => 'Mazowieckie', 'lat' => 52.2497, 'lng' => 21.0122],
            ['city' => 'Kraków', 'province' => 'Małopolskie', 'lat' => 50.0681, 'lng' => 19.9479], 
            ['city' => 'Gdańsk', 'province' => 'Pomorskie', 'lat' => 54.3478, 'lng' => 18.6496],
            ['city' => 'Wrocław', 'province' => 'Dolnośląskie', 'lat' => 51.0964, 'lng' => 17.0374],
            ['city' => 'Poznań', 'province' => 'Wielkopolskie', 'lat' => 52.4006, 'lng' => 16.9272],
        ];

        $location = fake()->randomElement($locations);
        $plFaker = fake('pl_PL'); 

        return [
            'company_name' => $plFaker->company(),
            'vat_id' => $plFaker->numerify('PL##########'), 
            'street_name' => $plFaker->streetName(),
            'street_number' => $plFaker->buildingNumber(),
            'city' => $location['city'],
            'zip_code' => $plFaker->postcode(), 
            'province' => $location['province'],
            
            'latitude' => $location['lat'] + fake()->randomFloat(6, -0.002, 0.002),
            'longitude' => $location['lng'] + fake()->randomFloat(6, -0.002, 0.002),
            
            'contact_person' => $plFaker->name(),
            'email' => fake()->unique()->companyEmail(),
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
            'latitude' => fake()->latitude(49.0, 54.8), 
            'longitude' => fake()->longitude(14.1, 24.1),
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