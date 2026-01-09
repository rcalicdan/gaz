<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_optimizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->date('optimization_date');
            $table->json('optimization_result');
            $table->json('pickup_sequence'); 
            $table->decimal('total_distance', 10, 2)->nullable();
            $table->integer('total_time')->nullable(); 
            $table->boolean('is_manual_edit')->default(false);
            $table->json('manual_modifications')->nullable();
            $table->boolean('requires_optimization')->default(false);
            $table->timestamps();

            $table->unique(['driver_id', 'optimization_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_optimizations');
    }
};