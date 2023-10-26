<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToCompaniesAndBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('companies', 'status')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('status')->default(1);
            });
        }

        if (!Schema::hasColumn('companies', 'subscription_start')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('subscription_start')->nullable();
            });
        }

        if (!Schema::hasColumn('companies', 'subscription_end')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('subscription_end')->nullable();
            });
        }

        if (!Schema::hasColumn('branches', 'status')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->string('status')->default('inactive');
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
