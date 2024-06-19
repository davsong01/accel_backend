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
        if(Schema::hasTable('construction_payment_threads')){
            Schema::rename('construction_payment_threads', 'contract_under_construction_threads');
            
            Schema::table('contract_under_construction_threads', function (Blueprint $table) {
                $table->renameColumn('construction_payment_id', 'contract_under_construction_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
