<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::with('user')->get();

        if ($drivers->isEmpty()) {
            $this->command->warn('No drivers found. Run DriverSeeder first.');
            return;
        }

        for ($i = 0; $i < 7; $i++) {
            foreach ($drivers as $driver) {
                Route::create([
                    'driver_id' => $driver->user_id,
                    'date' => now()->addDays($i),
                    'name' => $driver->user->name . ' - Route ' . now()->addDays($i)->format('Y-m-d'),
                    'status' => $i === 0 ? 'in_progress' : 'planned',
                ]);
            }
        }
    }
}