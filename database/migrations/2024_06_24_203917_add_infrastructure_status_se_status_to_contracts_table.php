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
        if (!Schema::hasColumns('customer_contracts', ['se_status','infrastructure_status'])) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('se_status')->after('status')->nullable();
                $table->string('infrastructure_status')->after('se_status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_contracts', function (Blueprint $table) {
            $table->dropColumn('se_status');
            $table->dropColumn('infrastructure_status');
        });
    }
};
