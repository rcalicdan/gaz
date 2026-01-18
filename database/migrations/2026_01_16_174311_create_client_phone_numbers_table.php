<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->string('label')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_phone_numbers');
    }
};