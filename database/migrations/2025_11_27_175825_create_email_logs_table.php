<?php

use App\Enums\DocumentType;
use App\Enums\EmailStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', DocumentType::values());
            $table->unsignedBigInteger('document_id');
            $table->string('recipient_email');
            $table->timestamp('sent_at')->useCurrent();
            $table->enum('status', EmailStatus::values())->default(EmailStatus::SENT->value);
            $table->text('error_message')->nullable();
            $table->foreignId('sent_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['document_type', 'document_id'], 'email_document_idx');
            $table->index('sent_at', 'email_sent_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};