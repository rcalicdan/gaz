<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('vat_id')->unique();
            $table->string('street_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('province')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email');
            $table->string('phone_number')->nullable();
            $table->string('brand_category')->nullable();
            $table->foreignId('default_waste_type_id')->nullable()->constrained('waste_types')->nullOnDelete();
            $table->integer('pickup_frequency_days')->nullable();
            $table->decimal('price_rate', 10, 2)->nullable();
            $table->string('currency')->default('PLN');
            $table->decimal('tax_rate', 5, 4)->default(0.23);
            $table->boolean('auto_invoice')->default(true);
            $table->boolean('auto_kpo')->default(true);
            $table->timestamp('last_contact_date')->nullable();
            $table->timestamp('last_pickup_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};