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
        if (Schema::hasColumn('transactions', 'transaction_type')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('transaction_type');
                $table->foreignId('transaction_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_type_id');
            $table->string('transaction_type');
        });
    }
};
