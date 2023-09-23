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
        Schema::create('transaction_type_definitions', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_type_id');
            $table->string('set_rules')->nullable();
            $table->string('set_attributes')->nullable();
            $table->integer('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_type_definitions');
    }
};
