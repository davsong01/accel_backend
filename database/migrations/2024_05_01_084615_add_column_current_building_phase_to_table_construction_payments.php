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
        if(!Schema::hasColumn('construction_payments', 'current_building_phase_id')){
            Schema::table('construction_payments', function (Blueprint $table) {
                $table->foreignId('current_building_phase_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('construction_payments', 'current_building_phase_id')){
            Schema::table('construction_payments', function (Blueprint $table) {
                $table->dropColumn('current_building_phase_id');
            });
        }
    }
};
