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
        Schema::create('estate_property_type_details', function (Blueprint $table) {
            $table->id();
            $table->integer('estate_id');
            $table->string('type');
            $table->string('status')->default(1);
            $table->string('plot_size')->nullable();
            $table->string('building_type')->nullable();
            $table->double('estate_price', 11, 2)->nullable();
            $table->string('settings_and_excavation')->nullable();
            $table->string('infrastructural_fee')->nullable();
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_property_type_details');
    }
};
