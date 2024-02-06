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
        Schema::create('staff_disengagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id'); 
            $table->foreignId('created_by'); 
            $table->foreignId('company_id'); 
            $table->text('reason');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamp('from')->default()->useCurrent();
            $table->timestamp('to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_disengagements');
    }
};
