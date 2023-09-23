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
            $table->string('customer_contract_ids');
            $table->integer('account_id');
            $table->integer('estate_id');
            $table->string('payment_slip')->nullable();
            $table->string('description')->nullable();
            $table->float('amount');
            $table->integer('company_id');
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
