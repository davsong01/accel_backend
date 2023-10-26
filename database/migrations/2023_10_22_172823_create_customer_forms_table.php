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
        Schema::create('customer_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_contract_id')->nullable();
            $table->integer('customer_id');
            $table->string('title')->nullable();
            $table->string('type')->default('response_form');
            $table->string('status')->default('pending');
            $table->string('no_of_contract')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('date_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            // nationality
            $table->integer('lga_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('email_address')->nullable();
            $table->text('contact_details')->nullable();
            $table->text('next_of_kin_information')->nullable();
            $table->text('means_of_identification')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('property_type_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('estate_id')->nullable();
            $table->integer('building_type_id')->nullable();
            $table->integer('plot_size_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->text('other_details')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->text('marketers')->nullable();
            $table->integer('bypass_approval')->nullable();
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_form_twos');
    }
};
