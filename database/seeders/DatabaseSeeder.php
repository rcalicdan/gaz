<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WasteTypeSeeder::class,
            PriceListSeeder::class,
            PriceListItemSeeder::class,
            DriverSeeder::class,
            ClientSeeder::class,
            RouteSeeder::class,
            RouteOptimizationSeeder::class,
            PickupSeeder::class,
            PickupBoxSeeder::class,
        ]);
    }
}