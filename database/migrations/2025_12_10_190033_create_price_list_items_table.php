<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained('price_lists')->onDelete('cascade');
            $table->foreignId('waste_type_id')->constrained('waste_types')->onDelete('cascade');
            $table->decimal('base_price', 10, 2)->comment('Base price per pickup/unit');
            $table->string('currency', 3)->default('PLN');
            $table->decimal('tax_rate', 5, 4)->default(0.23);
            $table->enum('unit_type', ['per_pickup', 'per_kg', 'per_ton', 'per_box'])->default('per_pickup');
            $table->decimal('min_quantity', 10, 2)->nullable()->comment('Minimum quantity for this price');
            $table->decimal('max_quantity', 10, 2)->nullable()->comment('Maximum quantity for this price');
            $table->timestamps();
            
            $table->index(['price_list_id', 'waste_type_id'], 'price_item_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_list_items');
    }
};