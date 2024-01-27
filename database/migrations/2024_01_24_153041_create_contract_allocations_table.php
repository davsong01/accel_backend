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
        Schema::create('contract_allocations', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('contract_id');
            $table->string('plot_number')->nullable();
            $table->string('building_number')->nullable();
            $table->integer('number_of_months')->nullable();
            $table->integer('staff_id')->nullable();
            $table->string('status')->default('active');
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_allocations');
    }
};
