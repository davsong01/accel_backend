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
        if(!Schema::hasColumn('customer_refunds', 'status')){
            Schema::table('customer_refunds', function (Blueprint $table) {
                $table->enum('status', ['PENDING', 'DECLINED', 'APPROVED'])
                ->default('PENDING')->after('comment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('customer_refunds', 'status')){
            Schema::table('customer_refunds', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
