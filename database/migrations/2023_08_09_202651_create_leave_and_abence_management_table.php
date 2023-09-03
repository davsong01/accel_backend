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
        Schema::create('leave_and_abence_management', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id');
            $table->string('period')->nullable();
            $table->string('reason')->nullable();
            $table->string('type')->nullable(); // Absence, Leave
            $table->string('status')->default('pending');
            $table->string('delegate_duty')->default('pending');
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_and_abence_management');
    }
};
