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
        Schema::create('generated_legal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_id');
            $table->foreignId('company_id');
            $table->foreignId('customer_id');
            $table->foreignId('legal_document_template_id');
            $table->integer('regenerate_count');
            $table->string('file_location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_legal_documents');
    }
};
