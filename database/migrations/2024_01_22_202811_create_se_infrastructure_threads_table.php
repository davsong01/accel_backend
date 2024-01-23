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
        Schema::create('se_infrastructure_threads', function (Blueprint $table) {
            $table->id();
            $table->integer('se_infrastructures_id');
            $table->double('amount', 11, 2);
            $table->integer('customer_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_slip')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('company_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('se_infrastructure_threads');
    }
};
