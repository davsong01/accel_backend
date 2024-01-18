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
        if (!Schema::hasColumn('customer_contracts', 'contract_status')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('contract_status')->nullable()->after('form_approval_status')->nullable();
            });
        }
        
        if (!Schema::hasColumn('contract_payments', 'contract_id')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->integer('contract_id')->nullable()->after('customer_id')->nullable();
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
