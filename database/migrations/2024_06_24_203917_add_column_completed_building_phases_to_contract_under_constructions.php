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
        Schema::table('contract_under_constructions', function (Blueprint $table) {
            $table->json('completed_building_phases')->after('estate_construction_setting_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_under_constructions', function (Blueprint $table) {
            $table->dropColumn('completed_building_phases');
        });
    }
};
