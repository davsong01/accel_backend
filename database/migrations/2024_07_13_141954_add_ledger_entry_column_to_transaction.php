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
        if(
            !Schema::hasColumn('transactions', 'balance_before') &&
            !Schema::hasColumn('transactions', 'balance_after')
        ){
            Schema::table('transactions', function (Blueprint $table) {
                $table->decimal('balance_before', 18, 2);
                 $table->decimal('balance_after', 18, 2);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(
            Schema::hasColumn('transactions', 'balance_before') &&
            Schema::hasColumn('transactions', 'balance_after')
        ){
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('balance_before');
                 $table->dropColumn('balance_after');
            });
        }
    }
};
