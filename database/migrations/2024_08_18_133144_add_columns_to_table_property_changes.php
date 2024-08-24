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
        if(Schema::hasColumn('property_changes', 'new_customer_contract_id')){
            Schema::table('property_changes', function (Blueprint $table) {
                $table->dropColumn('new_customer_contract_id');
            });
        }

        if(!Schema::hasColumns('property_changes', ['city_id', 'location_id', 'estate_id', 'plot_size_id'])){
            Schema::table('property_changes', function (Blueprint $table) {
                $table->foreignId('estate_id')->nullable()->after('original_customer_contract_id');
                $table->foreignId('city_id')->nullable()->after('estate_id');
                $table->foreignId('location_id')->nullable()->after('city_id');
                $table->foreignId('plot_size_id')->nullable()->after('location_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_changes', function (Blueprint $table) {
            //
        });
    }
};
