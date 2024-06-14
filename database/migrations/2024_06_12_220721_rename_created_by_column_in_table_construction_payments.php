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
        if(Schema::hasColumn('construction_payments', 'created_by')){
            Schema::table('construction_payments', function (Blueprint $table) {
                $table->renameColumn('created_by', 'created_by_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('construction_payments', 'created_by_id')){
            Schema::table('construction_payments', function (Blueprint $table) {
                $table->renameColumn('created_by_id', 'created_by');
            });
        }
    }
};
