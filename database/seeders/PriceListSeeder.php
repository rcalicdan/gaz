<?php

namespace Database\Seeders;

use App\Models\PriceList;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    public function run(): void
    {
        $priceLists = [
            [
                'name' => 'Standard Price List',
                'description' => 'Default pricing for standard clients',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Price List',
                'description' => 'Premium pricing for high-volume clients',
                'is_active' => true,
            ],
            [
                'name' => 'Budget Price List',
                'description' => 'Discounted pricing for small businesses',
                'is_active' => true,
            ],
            [
                'name' => 'Legacy Price List 2024',
                'description' => 'Archived pricing from 2024',
                'is_active' => false,
            ],
        ];

        foreach ($priceLists as $priceList) {
            PriceList::create($priceList);
        }
    }
}