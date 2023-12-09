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
        if (!Schema::hasColumn('legal_document_types','name')) {
            Schema::table('legal_document_types', function (Blueprint $table) {
                $table->string('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_document_types', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
