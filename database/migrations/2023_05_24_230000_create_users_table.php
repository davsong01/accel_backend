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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('user_permissions')->nullable();
            $table->integer('company_id');
            $table->string('email');
            $table->integer('staff_id')->unique();
            $table->integer('branch_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->enum('type', ['staff', 'customer']);
            $table->string('password')->nullable();
            $table->text('contact_information')->nullable();
            $table->text('additional_information')->nullable();
            $table->text('next_of_kin')->nullable();
            $table->text('job_information')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
