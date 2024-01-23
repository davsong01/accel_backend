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
        if (!Schema::hasColumn('transactions', 'transaction_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('transaction_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'transaction_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('transaction_id');
            });
        }
    }
};
