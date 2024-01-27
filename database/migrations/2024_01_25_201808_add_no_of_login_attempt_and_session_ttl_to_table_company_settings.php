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
        if(!Schema::hasColumn('company_settings', 'no_of_login_attempt')){
            Schema::table('company_settings', function (Blueprint $table) {
                $table->bigInteger('no_of_login_attempt')->nullable();
                $table->bigInteger('session_ttl')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('company_settings', 'no_of_login_attempt')){
            Schema::table('company_settings', function (Blueprint $table) {
                $table->dropColumn(['no_of_login_attempt', 'session_ttl']);
            });
        }   
    }
};
