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
        Schema::create('employee_exits', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_id');
            $table->timestamp('last_day_work')->nullable(); 
            $table->string('type')->nullable(); 
            $table->string('file')->nullable(); 
            $table->string('reason')->nullable(); 
            $table->string('comment')->nullable();
            $table->integer('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_exits');
    }
};
