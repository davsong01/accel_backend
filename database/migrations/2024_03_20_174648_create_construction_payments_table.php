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
        Schema::create('construction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('contract_id');
            $table->decimal('total_amount');
            $table->decimal('amount_paid');
            $table->json('estate_construction_setting_ids')->nullable();
            $table->foreignId('created_by');
            $table->foreignId('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_payments');
    }
};
