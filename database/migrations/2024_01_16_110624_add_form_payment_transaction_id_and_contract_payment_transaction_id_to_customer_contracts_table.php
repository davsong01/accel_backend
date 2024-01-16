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
        if (!Schema::hasColumn('customer_contracts', 'form_payment_transaction_id')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('form_payment_transaction_id')->nullable()->after('form_approval_status');;
            });
        }

        if (!Schema::hasColumn('customer_contracts', 'contract_payment_transaction_id')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('contract_payment_transaction_id')->nullable()->after('form_payment_transaction_id');;
            });
        }

        if (Schema::hasColumn('transactions', 'customer_contract_ids')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('customer_contract_ids');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_contracts', function (Blueprint $table) {
            $table->dropColumn('form_payment_transaction_id');
            $table->dropColumn('contract_payment_transaction_id');
        });

        if (!Schema::hasColumn('transactions', 'customer_contract_ids')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('customer_contract_ids')->nullable();
            });
        }
    }
};
