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
        Schema::create('clearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('customer_id');
            $table->foreignId('customer_contract_id');
            $table->enum('status', ['APPROVED', 'AWAITING_PAYMENT_APPROVAL', 'AWAITING_CONSTRUCTION_APPROVAL', 'DECLINED'])->default('AWAITING_PAYMENT_APPROVAL');
            $table->string('certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearances');
    }
};
