<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $driverUsers = User::where('role', UserRole::DRIVER)
            ->whereDoesntHave('driver')
            ->get();

        if ($driverUsers->isEmpty()) {
            $this->command->warn('No driver users found without driver records.');
            return;
        }

        foreach ($driverUsers as $user) {
            Driver::create([
                'user_id' => $user->id,
                'license_number' => 'DL-' . now()->year . '-' . strtoupper(substr(md5($user->email), 0, 6)),
            ]);
        }

        $this->command->info("Created {$driverUsers->count()} driver records.");
    }
}