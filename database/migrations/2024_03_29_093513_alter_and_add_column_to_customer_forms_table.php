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
        if (!Schema::hasColumn('customer_forms', 'type')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }

        if (!Schema::hasColumn('customer_forms', 'is_customer_response')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->tinyInteger('is_customer_response')->default(0);
            });
        }

        if (!Schema::hasColumn('customer_forms', 'is_form2_request')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->tinyInteger('is_form2_request')->default(0);
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_forms', function (Blueprint $table) {
            $table->dropColumn('is_form2_request');
            $table->dropColumn('is_customer_response');
        });
    }
};
