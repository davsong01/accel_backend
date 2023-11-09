<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormStatusToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customers', 'form_status')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('form_status')->nullable()->after('user_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn("category");
        });        
    }
}
