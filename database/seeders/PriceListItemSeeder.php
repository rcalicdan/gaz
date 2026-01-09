<?php

namespace Database\Seeders;

use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\WasteType;
use Illuminate\Database\Seeder;

class PriceListItemSeeder extends Seeder
{
    public function run(): void
    {
        $priceLists = PriceList::where('is_active', true)->get();
        $wasteTypes = WasteType::all();

        if ($priceLists->isEmpty() || $wasteTypes->isEmpty()) {
            $this->command->warn('No price lists or waste types found. Run WasteTypeSeeder and PriceListSeeder first.');
            return;
        }

        foreach ($priceLists as $priceList) {
            foreach ($wasteTypes as $wasteType) {
                PriceListItem::create([
                    'price_list_id' => $priceList->id,
                    'waste_type_id' => $wasteType->id,
                    'base_price' => rand(50, 500),
                    'currency' => 'PLN',
                    'tax_rate' => 0.23,
                    'unit_type' => 'per_pickup',
                    'min_quantity' => 0,
                    'max_quantity' => null,
                ]);
            }
        }
    }
}