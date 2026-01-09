<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('price_rate', 8, 2)->nullable()->change();
            $table->decimal('tax_rate', 5, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('price_rate', 5, 4)->nullable()->change();
            $table->decimal('tax_rate', 5, 4)->nullable()->change();
        });
    }
};