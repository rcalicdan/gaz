<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('price_list_id')->nullable()->after('default_waste_type_id')
                ->constrained('price_lists')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['price_list_id']);
            $table->dropColumn('price_list_id');
        });
    }
};