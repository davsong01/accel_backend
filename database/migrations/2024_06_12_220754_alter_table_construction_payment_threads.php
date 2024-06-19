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
        if(!Schema::hasColumn('construction_payment_threads', 'estate_construction_setting_ids')){
            Schema::table('construction_payment_threads', function (Blueprint $table) {
                $table->json('estate_construction_setting_ids')->nullable();
                $table->renameColumn('created_by', 'created_by_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('construction_payment_threads', function (Blueprint $table) {
            $table->renameColumn('created_by_id', 'created_by');
        });
    }
};
