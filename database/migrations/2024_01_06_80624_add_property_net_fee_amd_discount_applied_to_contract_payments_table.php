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
        if (!Schema::hasColumn('contract_payments', 'property_net_fee')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->double('property_net_fee', 11, 2)->nullable()->after('contract_value');;
            });
        }

        if (!Schema::hasColumn('contract_payments', 'discount_applied')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->double('discount_applied', 11, 2)->nullable()->after('property_net_fee');;
            });
        }

        if (!Schema::hasColumn('contract_payments', 'contract_id')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->integer('contract_id')->nullable()->after('id');;
            });
        }

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('contract_payments', 'discount_applied')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->dropColumn('discount_applied');
            });
        }

        if (Schema::hasColumn('contract_payments', 'property_net_fee')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->dropColumn('property_net_fee');
            });
        }
    }
};
