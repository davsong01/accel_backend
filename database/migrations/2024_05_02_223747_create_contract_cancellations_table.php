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
        Schema::create('contract_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('branch_id');
            $table->foreignId('customer_id');
            $table->foreignId('customer_contract_id');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_cancellations');
    }
};
