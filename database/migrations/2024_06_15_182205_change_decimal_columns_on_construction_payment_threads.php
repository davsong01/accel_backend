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
        if(Schema::hasColumn('construction_payment_threads', 'amount_paid')){
            Schema::table('construction_payment_threads', function (Blueprint $table) {
                $table->decimal('amount_paid', 18, 2)->change();
                $table->decimal('balance_before', 18, 2)->change();
                $table->decimal('balance_after', 18, 2)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('construction_payment_threads', function (Blueprint $table) {
            //
        });
    }
};
