<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\PriceList;
use App\Models\WasteType;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $priceList = PriceList::where('is_active', true)->first();
        $wasteType = WasteType::first();

        if (!$priceList || !$wasteType) {
            $this->command->warn('No price lists or waste types found. Run those seeders first.');
            return;
        }

        $hubs = [
            ['city' => 'Warszawa', 'province' => 'Mazowieckie', 'lat' => 52.2302, 'lng' => 21.0032],
            ['city' => 'Kraków', 'province' => 'Małopolskie', 'lat' => 50.0681, 'lng' => 19.9479],
            ['city' => 'Gdańsk', 'province' => 'Pomorskie', 'lat' => 54.3478, 'lng' => 18.6496],
            ['city' => 'Wrocław', 'province' => 'Dolnośląskie', 'lat' => 51.0964, 'lng' => 17.0374],
            ['city' => 'Poznań', 'province' => 'Wielkopolskie', 'lat' => 52.4006, 'lng' => 16.9272],
        ];

        $fakerPl = fake('pl_PL');

        foreach ($hubs as $hub) {
            $clients = Client::factory()
                ->count(5)
                ->state(function (array $attributes) use ($hub, $fakerPl) {
                    $hasDifferentPremises = fake()->boolean(70);
                    
                    $state = [
                        'registered_city' => $hub['city'],
                        'registered_province' => $hub['province'],
                    ];
                    
                    if ($hasDifferentPremises) {
                        $state['premises_street_name'] = fake()->streetName();
                        $state['premises_street_number'] = fake()->buildingNumber();
                        $state['premises_city'] = $hub['city'];
                        $state['premises_zip_code'] = $fakerPl->postcode();
                        $state['premises_province'] = $hub['province'];
                        $state['premises_latitude'] = $hub['lat'] + fake()->randomFloat(5, -0.04, 0.04);
                        $state['premises_longitude'] = $hub['lng'] + fake()->randomFloat(5, -0.06, 0.06);
                    } else {
                        $state['premises_latitude'] = $hub['lat'] + fake()->randomFloat(5, -0.04, 0.04);
                        $state['premises_longitude'] = $hub['lng'] + fake()->randomFloat(5, -0.06, 0.06);
                    }
                    
                    return $state;
                })
                ->create([
                    'price_list_id' => $priceList->id,
                    'default_waste_type_id' => $wasteType->id,
                ]);

            $this->addPhoneNumbersToClients($clients, $fakerPl);
        }

        $unlocatedClients = Client::factory()
            ->count(3)
            ->withoutCoordinates()
            ->create([
                'price_list_id' => $priceList->id,
                'default_waste_type_id' => $wasteType->id,
            ]);

        $this->addPhoneNumbersToClients($unlocatedClients, $fakerPl);
            
        $this->command->info('Clients and phone numbers seeded successfully across ' . count($hubs) . ' Polish cities.');
        $this->command->info('Mix of clients with separate premises addresses and those using registered address.');
    }

    private function addPhoneNumbersToClients($clients, $faker)
    {
        foreach ($clients as $client) {
            $client->phoneNumbers()->create([
                'phone_number' => '+48 ' . $faker->numerify('### ### ###'),
                'label' => 'Main',
                'is_primary' => true,
            ]);

            if (fake()->boolean(30)) {
                $client->phoneNumbers()->create([
                    'phone_number' => '+48 ' . $faker->numerify('### ### ###'),
                    'label' => fake()->randomElement(['Mobile', 'Billing', 'Logistics']),
                    'is_primary' => false,
                ]);
            }
        }
    }
}