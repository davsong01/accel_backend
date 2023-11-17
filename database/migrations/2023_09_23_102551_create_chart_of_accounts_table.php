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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('accounttype_id')->nullable();
            $table->integer('account_category_id')->nullable();
            $table->integer('currency')->nullable();
            $table->enum('input_method', ['direct', 'manueal'])->nullable();
            $table->integer('company_id');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
