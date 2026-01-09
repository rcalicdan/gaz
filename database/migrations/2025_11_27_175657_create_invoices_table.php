<?php

use App\Enums\KsefStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique()->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('net_amount', 10, 2)->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('gross_amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->enum('ksef_status', KsefStatus::values())->default(KsefStatus::PENDING->value);
            $table->string('ksef_reference_number')->nullable();
            $table->text('ksef_xml_content')->nullable();
            $table->string('pdf_url')->nullable();
            $table->boolean('is_emailed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};