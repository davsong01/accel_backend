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
        Schema::create('se_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 11, 2);
            $table->double('balance', 11, 2);
            $table->double('infrastructure_fee', 11, 2);
            $table->integer('contract_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('type')->nullable(); //se, infrastructure
            $table->integer('account_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('payment_slip')->nullable();
            $table->string('receipt_location')->nullable();
            
            $table->integer('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('se_infrastructures');
    }
};
