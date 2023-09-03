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
        Schema::create('contract_customer', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('city_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('estate_id')->nullable();
            $table->text('subscribers')->nullable();
            $table->string('plot_size')->nullable();
            $table->string('building_type')->nullable();
            $table->string('property_type')->nullable();
            $table->enum('status', ['lead', 'prospect', 'subscriber']);
            $table->integer('company_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_customers');
    }
};
