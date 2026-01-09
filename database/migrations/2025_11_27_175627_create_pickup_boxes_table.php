<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_id')->constrained()->cascadeOnDelete();
            $table->string('box_number');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['pickup_id', 'box_number'], 'pickup_box_unique_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_boxes');
    }
};