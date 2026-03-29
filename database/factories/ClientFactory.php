<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientPhoneNumber;
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

        $hasDifferentPremises = fake()->boolean(60);

        $data = [
            'company_name' => fake()->company(),
            'vat_id' => fake()->regexify('[1-9]{1}[0-9]{9}'),

            'registered_street_name' => fake()->streetName(),
            'registered_street_number' => fake()->buildingNumber(),
            'registered_city' => $location['city'],
            'registered_zip_code' => $plFaker->postcode(),
            'registered_province' => $location['province'],

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

        if ($hasDifferentPremises) {
            $premisesLocation = fake()->randomElement($locations);
            $data['premises_street_name'] = fake()->streetName();
            $data['premises_street_number'] = fake()->buildingNumber();
            $data['premises_city'] = $premisesLocation['city'];
            $data['premises_zip_code'] = $plFaker->postcode();
            $data['premises_province'] = $premisesLocation['province'];
            $data['premises_latitude'] = $premisesLocation['lat'] + fake()->randomFloat(6, -0.002, 0.002);
            $data['premises_longitude'] = $premisesLocation['lng'] + fake()->randomFloat(6, -0.002, 0.002);
        } else {
            $data['premises_street_name'] = null;
            $data['premises_street_number'] = null;
            $data['premises_city'] = null;
            $data['premises_zip_code'] = null;
            $data['premises_province'] = null;
            $data['premises_latitude'] = $location['lat'] + fake()->randomFloat(6, -0.002, 0.002);
            $data['premises_longitude'] = $location['lng'] + fake()->randomFloat(6, -0.002, 0.002);
        }

        return $data;
    }

    public function withCoordinates(): static
    {
        return $this->state(fn(array $attributes) => [
            'premises_latitude' => fake()->latitude(49.0, 54.8),
            'premises_longitude' => fake()->longitude(14.1, 24.1),
        ]);
    }

    public function withoutCoordinates(): static
    {
        return $this->state(fn(array $attributes) => [
            'premises_latitude' => null,
            'premises_longitude' => null,
        ]);
    }

    public function withSeparatePremises(): static
    {
        $locations = [
            ['city' => 'Warszawa', 'province' => 'Mazowieckie', 'lat' => 52.2302, 'lng' => 21.0032],
            ['city' => 'Kraków', 'province' => 'Małopolskie', 'lat' => 50.0681, 'lng' => 19.9479],
            ['city' => 'Gdańsk', 'province' => 'Pomorskie', 'lat' => 54.3478, 'lng' => 18.6496],
        ];

        $location = fake()->randomElement($locations);
        $plFaker = fake('pl_PL');

        return $this->state(fn(array $attributes) => [
            'premises_street_name' => fake()->streetName(),
            'premises_street_number' => fake()->buildingNumber(),
            'premises_city' => $location['city'],
            'premises_zip_code' => $plFaker->postcode(),
            'premises_province' => $location['province'],
            'premises_latitude' => $location['lat'] + fake()->randomFloat(6, -0.002, 0.002),
            'premises_longitude' => $location['lng'] + fake()->randomFloat(6, -0.002, 0.002),
        ]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Client $client) {
            ClientPhoneNumber::factory()
                ->primary()
                ->create([
                    'client_id' => $client->id,
                    'label' => 'Główny',
                ]);

            if (fake()->boolean(30)) {
                ClientPhoneNumber::factory()
                    ->create([
                        'client_id' => $client->id,
                        'label' => fake()->randomElement(['Biuro', 'Komórka']),
                    ]);
            }
        });
    }
}
