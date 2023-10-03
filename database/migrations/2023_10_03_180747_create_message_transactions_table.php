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
        Schema::create('message_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('provider_id');
            $table->string('recipient');
            $table->string('from')->nullable();
            $table->text('message');
            $table->string('status')->default('pending');
            $table->string('medium');
            $table->string('subject')->nullable();
            $table->dateTime('schedule_date');
            $table->string('purpose')->nullable();
            $table->integer('company_id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_transactions');
    }
};
