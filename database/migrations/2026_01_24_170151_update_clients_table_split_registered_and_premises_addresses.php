<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('street_name', 'registered_street_name');
            $table->renameColumn('street_number', 'registered_street_number');
            $table->renameColumn('city', 'registered_city');
            $table->renameColumn('zip_code', 'registered_zip_code');
            $table->renameColumn('province', 'registered_province');
            
            $table->renameColumn('latitude', 'premises_latitude');
            $table->renameColumn('longitude', 'premises_longitude');
        });
        
        Schema::table('clients', function (Blueprint $table) {
            $table->string('premises_street_name')->nullable()->after('registered_province');
            $table->string('premises_street_number')->nullable()->after('premises_street_name');
            $table->string('premises_city')->nullable()->after('premises_street_number');
            $table->string('premises_zip_code')->nullable()->after('premises_city');
            $table->string('premises_province')->nullable()->after('premises_zip_code');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'premises_street_name',
                'premises_street_number',
                'premises_city',
                'premises_zip_code',
                'premises_province',
            ]);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('registered_street_name', 'street_name');
            $table->renameColumn('registered_street_number', 'street_number');
            $table->renameColumn('registered_city', 'city');
            $table->renameColumn('registered_zip_code', 'zip_code');
            $table->renameColumn('registered_province', 'province');
            
            $table->renameColumn('premises_latitude', 'latitude');
            $table->renameColumn('premises_longitude', 'longitude');
        });
    }
};