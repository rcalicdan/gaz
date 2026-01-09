<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpo_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('kpo_number')->nullable();
            $table->string('waste_code')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('pdf_url')->nullable();
            $table->boolean('is_emailed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpo_documents');
    }
};