<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('generated_legal_documents', 'legal_document_type_id')) {
            Schema::table('generated_legal_documents', function (Blueprint $table) {
                $table->integer('legal_document_type_id')->nullable()->after('legal_document_template_id');;
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('generated_legal_documents', 'legal_document_type_id')) {
            Schema::table('generated_legal_documents', function (Blueprint $table) {
                $table->dropColumn('legal_document_type_id');
            });
        }
    }
};
