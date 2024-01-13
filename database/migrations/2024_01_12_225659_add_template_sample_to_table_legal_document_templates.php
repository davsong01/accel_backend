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
        if (!Schema::hasColumn('legal_document_templates', 'template_sample')) {
            Schema::table('legal_document_templates', function (Blueprint $table) {
                $table->string('template_sample')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('legal_document_templates', 'template_sample')) {
            Schema::table('legal_document_templates', function (Blueprint $table) {
                $table->dropColumn(['template_sample']);
            });
        }
    }
};
