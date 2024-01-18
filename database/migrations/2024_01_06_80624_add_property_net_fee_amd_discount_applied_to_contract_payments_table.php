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
                $table->double('property_net_fee',11,2)->nullable()->after('contract_value');;
            });
        }

        if (!Schema::hasColumn('contract_payments', 'dicount_applied')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->double('dicount_applied', 11, 2)->nullable()->after('property_net_fee');;
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('contract_payments', 'dicount_applied')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->dropColumn('dicount_applied');
            });
        }

        if (Schema::hasColumn('contract_payments', 'property_net_fee')) {
            Schema::table('contract_payments', function (Blueprint $table) {
                $table->dropColumn('property_net_fee');
            });
        }
    }
};
