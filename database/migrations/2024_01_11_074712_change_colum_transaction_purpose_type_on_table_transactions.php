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
        if (Schema::hasColumn('transactions', 'purpose')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('purpose');
                $table->foreignId('transaction_purpose_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_purpose_id');
            $table->string('purpose');
        });
    }
};
