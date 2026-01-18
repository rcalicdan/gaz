<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('vat_id');
            $table->date('contract_signed_date')->nullable()->after('contract_number');
            
            $table->index('contract_number');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['contract_number']);
            $table->dropColumn(['contract_number', 'contract_signed_date']);
        });
    }
};