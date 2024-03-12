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
        Schema::create('get_started', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_size')->nullable()->comment('0-10|11-30|31-60|60-above');
            $table->string('industry')->nullable()->comment('real-estate-agencies|property-management-companies| construction-and-management|healthcare-facilities|hospitality-and-tourism|education-institutions|transportation-and-logistics|agriculture-and-farming,others');
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('get_started');
    }
};
