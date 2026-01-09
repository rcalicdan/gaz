<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;

class WasteTypeSeeder extends Seeder
{
    public function run(): void
    {
        $wasteTypes = [
            ['code' => '150101', 'name' => 'Paper and cardboard packaging', 'description' => 'Packaging materials made of paper and cardboard'],
            ['code' => '150102', 'name' => 'Plastic packaging', 'description' => 'Packaging materials made of plastic'],
            ['code' => '150103', 'name' => 'Wooden packaging', 'description' => 'Packaging materials made of wood'],
            ['code' => '200101', 'name' => 'Paper and cardboard', 'description' => 'General paper and cardboard waste'],
            ['code' => '200139', 'name' => 'Plastics', 'description' => 'General plastic waste'],
            ['code' => '170201', 'name' => 'Wood', 'description' => 'Wooden waste materials'],
            ['code' => '200140', 'name' => 'Metal', 'description' => 'Metal waste materials'],
            ['code' => '200102', 'name' => 'Glass', 'description' => 'Glass waste materials'],
        ];

        foreach ($wasteTypes as $type) {
            WasteType::create($type);
        }
    }
}