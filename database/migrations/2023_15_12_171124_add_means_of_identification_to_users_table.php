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
        if (!Schema::hasColumn('users', 'means_of_identification')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('means_of_identification')->nullable()->after('additional_details');;
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'means_of_identification')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('means_of_identification');
            });
        }
    }
};
