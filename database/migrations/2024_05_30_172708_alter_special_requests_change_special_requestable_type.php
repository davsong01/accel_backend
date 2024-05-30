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
        if(Schema::hasColumn('special_requests', 'special_requestable_type')){
            Schema::table('special_requests', function (Blueprint $table) {
                $table->string('special_requestable_type')->change()->after('special_requestable_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('special_requests', function (Blueprint $table) {
            //
        });
    }
};
