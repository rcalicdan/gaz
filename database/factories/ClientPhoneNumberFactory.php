<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientPhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientPhoneNumberFactory extends Factory
{
    protected $model = ClientPhoneNumber::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'phone_number' => fake()->phoneNumber(),
            'label' => fake()->randomElement(['Główny', 'Biuro', 'Komórka', 'Faks']),
            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}
