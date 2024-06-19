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
        if(Schema::hasColumn('clearances', 'status')){
            Schema::table('clearances', function (Blueprint $table) {
                $table->enum('status', [
                    'APPROVED', 'AWAITING_PAYMENT_APPROVAL', 'AWAITING_CONSTRUCTION_APPROVAL', 'DECLINED', 'PAYMENT_APPROVAL_DECLINED', 'CONSTRUCTION_APPROVAL_DECLINED'])
                ->default('AWAITING_PAYMENT_APPROVAL')
                ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clearances', function (Blueprint $table) {
            //
        });
    }
};
