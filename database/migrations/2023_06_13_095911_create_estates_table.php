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
        Schema::create('estates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city_id');
            $table->string('location_id');
            $table->string('status')->default(1);
            $table->string('company_id');
            $table->string('project_supervisor')->nullable();
            $table->string('property_type')->nullable();
            $table->string('description')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('bypass_approval')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
