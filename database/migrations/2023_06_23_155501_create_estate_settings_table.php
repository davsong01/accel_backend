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
        Schema::create('estate_settings', function (Blueprint $table) {
            $table->id();
            $table->float('form_payment_for_land',11,2)->nullable();
            $table->float('form_payment_for_building', 11, 2)->nullable();
            $table->string('plot_size_unit')->nullable();
            $table->integer('form_expiry_date')->nullable();            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_settings');
    }
};
