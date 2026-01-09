<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\RouteOptimization;
use Illuminate\Database\Seeder;

class RouteOptimizationSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::all();

        if ($drivers->isEmpty()) {
            $this->command->warn('No drivers found. Run DriverSeeder first.');
            return;
        }

        foreach ($drivers as $driver) {
            for ($i = 0; $i < 5; $i++) {
                RouteOptimization::create([
                    'driver_id' => $driver->id,
                    'optimization_date' => now()->addDays($i),
                    'optimization_result' => [
                        'status' => 'optimized',
                        'algorithm' => 'vroom',
                    ],
                    'pickup_sequence' => [1, 2, 3, 4, 5],
                    'total_distance' => rand(100, 250),
                    'total_time' => rand(240, 420),
                    'is_manual_edit' => false,
                    'manual_modifications' => null,
                    'requires_optimization' => false,
                ]);
            }
        }
    }
}