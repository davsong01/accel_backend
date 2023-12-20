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
        if(!Schema::hasColumn('generated_legal_documents', 'contract_id')){
            Schema::table('generated_legal_documents', function (Blueprint $table) {
                $table->foreignId('contract_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_legal_documents', function (Blueprint $table) {
            $table->dropColumn('contract_id');
        });
    }
};
