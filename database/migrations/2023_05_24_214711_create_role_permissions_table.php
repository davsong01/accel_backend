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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role_ids')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->enum('type', ['admin', 'customer']);
            $table->enum('is_menu', ['yes', 'no']);
            $table->string('menu_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('route');
            $table->string('icon')->nullable();
            $table->integer('parent')->nullable();
            $table->char('order')->nullable();
            $table->integer('company_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
