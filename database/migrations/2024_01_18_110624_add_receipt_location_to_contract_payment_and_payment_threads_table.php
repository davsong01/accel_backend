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
        if (!Schema::hasColumn('contract_payments', 'receipt_location')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->string('receipt_location')->nullable()->after('payment_slip')->nullable();
            });
        }
        
        if (!Schema::hasColumn('contract_payment_threads', 'receipt_location')) {
            Schema::table('contract_payment_threads', function (Blueprint $table) {
                $table->string('receipt_location')->nullable()->after('payment_slip')->nullable();
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('customer_contracts', 'contract_status')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('contract_status')->nullable();
            });
        }

        if (!Schema::hasColumn('contract_payments', 'contract_id')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->string('contract_id')->nullable();
            });
        }
    }
};
