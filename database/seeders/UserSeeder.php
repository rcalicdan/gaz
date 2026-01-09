<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createDefaultUsers();
        $this->createAdmins();
        $this->createEmployees();
        $this->createDrivers();
        
        $this->displayCredentials();
    }

    private function createDefaultUsers(): void
    {
        $defaultUsers = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => UserRole::ADMIN,
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Employee',
                'email' => 'employee@example.com',
                'role' => UserRole::EMPLOYEE,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Driver',
                'email' => 'driver@example.com',
                'role' => UserRole::DRIVER,
            ],
        ];

        foreach ($defaultUsers as $userData) {
            User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => bcrypt('password'),
                'role' => $userData['role'],
                'active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }

    private function createAdmins(): void
    {
        User::factory()
            ->admin()
            ->active()
            ->count(2)
            ->create();
    }

    private function createEmployees(): void
    {
        User::factory()
            ->employee()
            ->active()
            ->count(8)
            ->create();

        User::factory()
            ->employee()
            ->inactive()
            ->count(2)
            ->create();

        User::factory()
            ->employee()
            ->unverified()
            ->count(1)
            ->create();
    }

    private function createDrivers(): void
    {
        User::factory()
            ->driver()
            ->active()
            ->count(5)
            ->create();

        User::factory()
            ->driver()
            ->active()
            ->count(3)
            ->create();

        User::factory()
            ->driver()
            ->inactive()
            ->count(1)
            ->create();
    }

    private function displayCredentials(): void
    {
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('  Users Seeded Successfully!');
        $this->command->info('═══════════════════════════════════════');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@example.com', 'password'],
                ['Employee', 'employee@example.com', 'password'],
                ['Driver', 'driver@example.com', 'password'],
            ]
        );
        $this->command->newLine();
    }
}