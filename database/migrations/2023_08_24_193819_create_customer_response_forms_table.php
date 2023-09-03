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
        Schema::create('customer_response_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->datetime('date_completed')->nullable();
            $table->text('personal_details')->nullable();
            $table->text('contact-address')->nullable();
            $table->text('next_of_kin')->nullable();
            $table->text('identification')->nullable();
            $table->text('uploaded_documents')->nullable();
            $table->text('contract_type')->nullable();
            $table->integer('status')->default(0);
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_response_forms');
    }
};
