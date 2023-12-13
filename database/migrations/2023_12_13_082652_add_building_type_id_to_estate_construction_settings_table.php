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
        if (!Schema::hasColumn('estate_construction_settings', 'building_type_id')) {
            Schema::table('estate_construction_settings', function (Blueprint $table) {
                $table->foreignId('building_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estate_construction_settings', function (Blueprint $table) {
            $table->dropColumn('building_type_id');
        });
    }
};
