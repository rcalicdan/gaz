<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('e.g., "Standard Pricing 2024", "Premium Clients"');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_default')->default(false)->comment('One default price list for new clients');
            $table->timestamps();
            
            $table->index('effective_from', 'price_list_effective_idx');
            $table->index('is_active', 'price_list_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};