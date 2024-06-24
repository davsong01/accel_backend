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
        Schema::table('leave_and_absence_management', function (Blueprint $table) {
            $table->dateTime('resumption_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_and_absence_management', function (Blueprint $table) {
            $table->dropColumn('resumption_date');
        });
    }
};
