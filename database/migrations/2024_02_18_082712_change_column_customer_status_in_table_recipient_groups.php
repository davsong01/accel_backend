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
        Schema::table('recipient_groups', function (Blueprint $table) {
            $table->json('customer_status')->nullable()->change();
            $table->json('subscriber_status')->nullable()->change();
            $table->json('completed_subscriber_status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipient_groups', function (Blueprint $table) {
            //
        });
    }
};
