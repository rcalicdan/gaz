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

        Client::factory()
            ->count(20)
            ->withCoordinates()
            ->create([
                'price_list_id' => $priceList->id,
                'default_waste_type_id' => $wasteType->id,
            ]);

        Client::factory()
            ->count(5)
            ->withoutCoordinates()
            ->create([
                'price_list_id' => $priceList->id,
                'default_waste_type_id' => $wasteType->id,
            ]);
    }
}