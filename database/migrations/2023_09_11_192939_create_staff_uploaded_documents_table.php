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
        Schema::create('staff_uploaded_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_document_id');
            $table->integer('staff_id');
            $table->string('name')->nullable();
            $table->string('uploaded')->nullable();
            $table->integer('required')->nullable();
            $table->integer('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_uploaded_documents');
    }
};
