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
        if (Schema::hasColumn('customer_forms', 'transaction_date')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->dropColumn('transaction_date');
            });
        }

        if (!Schema::hasColumn('customer_contracts', 'transaction_date')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->datetime('transaction_date')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customer_contracts', 'transaction_date')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->dropColumn('transaction_date');
            });
        }
    }
};
