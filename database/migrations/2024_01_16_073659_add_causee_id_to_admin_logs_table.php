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
        if(!Schema::hasColumn('admin_logs', 'causee_id')){
            Schema::table('admin_logs', function (Blueprint $table) {
                $table->foreignId('causee_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('admin_logs', 'causee_id')){
            Schema::table('admin_logs', function (Blueprint $table) {
                $table->dropColumn('causee_id');
            });
        }
    }
};
