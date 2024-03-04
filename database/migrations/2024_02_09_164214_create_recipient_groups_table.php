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
        Schema::create('recipient_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('name');
            $table->foreignId('created_by');
            $table->boolean('all_customers')->nullable();
            $table->enum('customer_status', ['active', 'inactive'])->nullable();
            $table->enum('subscriber_status', ['active', 'inactive'])->nullable();
            $table->enum('completed_subscriber_status', ['active', 'inactive'])->nullable();
            $table->json('location_ids')->nullable();
            $table->json('estate_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipient_groups');
    }
};
