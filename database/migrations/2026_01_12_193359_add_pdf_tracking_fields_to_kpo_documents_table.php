<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kpo_documents', function (Blueprint $table) {
            $table->dropColumn('pdf_url');
            
            $table->string('pdf_path')->nullable()->after('additional_notes');
            $table->integer('pdf_version')->default(1)->after('pdf_path');
            $table->timestamp('pdf_generated_at')->nullable()->after('pdf_version');
            
            $table->index('kpo_number');
            $table->index('pdf_generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('kpo_documents', function (Blueprint $table) {
            $table->dropIndex(['kpo_number']);
            $table->dropIndex(['pdf_generated_at']);
            
            $table->dropColumn([
                'pdf_path',
                'pdf_version',
                'pdf_generated_at',
            ]);
            
            $table->string('pdf_url')->nullable()->after('additional_notes');
        });
    }
};