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
        if (!Schema::hasColumn('customer_contracts', 'sei_payment_transaction_id')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('sei_payment_transaction_id')->nullable()->after('form_payment_transaction_id');;
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_contracts', function (Blueprint $table) {
            $table->dropColumn('sei_payment_transaction_id');
        });

    }
};
