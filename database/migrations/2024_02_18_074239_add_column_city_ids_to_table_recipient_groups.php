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
        if(!Schema::hasColumn('recipient_groups', 'city_ids')){
            Schema::table('recipient_groups', function (Blueprint $table) {
                $table->json('city_ids')->after('completed_subscriber_status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipient_groups', function (Blueprint $table) {
            $table->dropColumn('city_ids');
        });
    }
};
