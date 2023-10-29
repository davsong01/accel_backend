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
        Schema::create('advance_and_claims', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id');
            $table->integer('period')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->default('pending');
            $table->enum('type', ['advance','claims']);
            $table->enum('bypass_approval', ['Yes','No'])->default('Yes');
            $table->string('status')->default('pending');
            $table->string('time_sensitivity')->nullable();
            $table->text('proof')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_and_claims');
    }
};
