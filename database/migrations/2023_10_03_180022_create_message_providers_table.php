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
        Schema::create('message_providers', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('email'); // email, sms
            $table->string('name')->nullable();
            $table->string('status')->default('inactive');
            $table->string('service')->default('smtp'); // smtp, others
            $table->json('credentials')->nullable();
            $table->integer('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_providers');
    }
};
