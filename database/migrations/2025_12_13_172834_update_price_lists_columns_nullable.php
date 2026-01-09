<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->date('effective_from')->nullable()->change();
            $table->boolean('is_default')->default(false)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->date('effective_from')->nullable(false)->change();
            $table->boolean('is_default')->default(false)->nullable(false)->change();
        });
    }
};