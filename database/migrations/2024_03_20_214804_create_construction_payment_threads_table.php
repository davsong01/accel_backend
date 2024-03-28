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
        Schema::create('construction_payment_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('created_by');
            $table->foreignId('construction_payment_id');
            $table->foreignId('chart_of_account_id');
            $table->string('payment_slip')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount_paid');
            $table->decimal('balance_before');
            $table->decimal('balance_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_payment_threads');
    }
};
