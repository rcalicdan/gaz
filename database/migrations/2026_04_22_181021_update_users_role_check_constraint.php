<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        
        $roles = implode(', ', array_map(fn($role) => "'{$role}'", UserRole::values()));
        
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text IN ({$roles}))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        
        $roles = collect(UserRole::values())
            ->filter(fn($role) => $role !== UserRole::CLIENT->value)
            ->map(fn($role) => "'{$role}'")
            ->implode(', ');

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text IN ({$roles}))");
    }
};