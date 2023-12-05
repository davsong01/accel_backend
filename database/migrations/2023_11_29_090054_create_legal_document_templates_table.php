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
        Schema::create('legal_document_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId(
                'estate_id'
            )->constrained();
            $table->foreignId(
                'company_id'
            )->constrained();
            $table->foreignId(
                'created_by'
            )->constrained('users');
            $table->foreignId('legal_document_type_id')
            ->constrained();
            $table->foreignId('property_type_id')
            ->constrained();
            $table->string('file_location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_document_templates');
    }
};
