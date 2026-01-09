<?php

use App\Enums\DocumentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', DocumentType::values());
            $table->unsignedBigInteger('document_id');
            $table->timestamp('printed_at')->useCurrent();
            $table->foreignId('printed_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('copies')->default(1);
            $table->timestamps();

            $table->index(['document_type', 'document_id'], 'print_document_idx');
            $table->index('printed_at', 'print_printed_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_logs');
    }
};