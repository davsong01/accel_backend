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
        if (!Schema::hasColumn('customer_contracts', 'form_approval_status')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('form_approval_status')->nullable()->after('status');;
            });
        }

        if (!Schema::hasColumn('customer_contracts', 'reason')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->string('reason')->nullable()->after('form_approval_status');;
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customer_contracts', 'reason')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->dropColumn('reason');
            });
        }

        if (Schema::hasColumn('customer_contracts', 'form_approval_status')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->dropColumn('form_approval_status');
            });
        }
    }
};
