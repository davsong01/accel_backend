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
        if (!Schema::hasTable('contract_payments')) {
            Schema::create('contract_payments', function (Blueprint $table) {
                $table->id();
                $table->double('amount', 11, 2);
                $table->double('balance', 11, 2);
                $table->string('transaction_id')->nullable();
                $table->integer('account_id')->nullable();
                $table->integer('customer_id')->nullable();
                $table->integer('branch_id')->nullable();
                $table->integer('no_of_months')->nullable();
                $table->double('contract_value', 11, 2);
                $table->text('description')->nullable();
                $table->string('payment_slip')->nullable();

                $table->integer('company_id')->nullable();
    
                $table->timestamps();
            });
        }

        // if (!Schema::hasColumns('customer_contracts', ['allow_installment', 'no_of_months'])) {
        //     Schema::table('customer_contracts', function (Blueprint $table) {
        //         $table->integer('allow_installment')->default(0);
        //         $table->integer('no_of_months')->nullable();
        //     });
        // }    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_payments');
    }
};
