<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignIdsToStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('staff', 'department_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->integer('department_id')->nullable();
            });
        }

        if (!Schema::hasColumn('staff', 'jobrole_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->integer('jobrole_id')->nullable();
            });
        }

        if (!Schema::hasColumn('staff', 'jobtype_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->integer('jobtype_id')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'commission')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->double('commission')->nullable();
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
        
    }
}
