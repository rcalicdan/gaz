<?php

use App\Enums\PickupStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->integer('sequence_order')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->timestamp('actual_pickup_time')->nullable();
            $table->enum('status', PickupStatus::values())->default(PickupStatus::SCHEDULED->value);
            $table->decimal('waste_quantity', 10, 2)->nullable();
            $table->foreignId('waste_type_id')->nullable()->constrained()->nullOnDelete();
            $table->text('driver_note')->nullable();
            $table->decimal('applied_price_rate', 10, 2)->nullable();
            $table->string('certificate_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
};