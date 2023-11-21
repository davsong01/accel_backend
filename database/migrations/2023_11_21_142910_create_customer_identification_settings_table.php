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
        Schema::create('customer_identification_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('use_id_generator')->default(0);
            $table->string('next_auto_increment_number')->nullable();
            $table->string('auto_increment_number_digit')->nullable();
            $table->string('appearance_format')->nullable();
            $table->string('prefix')->nullable();
            $table->string('order_of_appearance')->nullable();
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_identification_settings');
    }
};
