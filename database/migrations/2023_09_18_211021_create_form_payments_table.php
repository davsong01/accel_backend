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
        Schema::create('form_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_contract_id');
            $table->integer('account_id');
            $table->string('payment_slip')->nullable();
            $table->string('description')->nullable();
            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_payments');
    }
};
