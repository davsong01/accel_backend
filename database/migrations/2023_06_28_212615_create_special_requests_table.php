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
        Schema::create('special_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('branch_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('current_contract')->nullable();
            $table->string('new_contract')->nullable();
            $table->float('termination_charge')->nullable();
            $table->integer('company_id');
            $table->integer('original_owner_id')->nullable();
            $table->integer('new_owner_id')->nullable();
            $table->double('amount', 11, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_requests');
    }
};
