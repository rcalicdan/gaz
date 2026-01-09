<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_price_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('waste_type_id')->constrained('waste_types')->onDelete('cascade');
            $table->decimal('custom_price', 10, 2);
            $table->string('currency', 3)->default('PLN');
            $table->decimal('tax_rate', 5, 4)->default(0.23);
            $table->enum('unit_type', ['per_pickup', 'per_kg', 'per_ton', 'per_box'])->default('per_pickup');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->text('notes')->nullable()->comment('Reason for custom pricing');
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['client_id', 'waste_type_id', 'effective_from'], 'client_price_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_price_overrides');
    }
};