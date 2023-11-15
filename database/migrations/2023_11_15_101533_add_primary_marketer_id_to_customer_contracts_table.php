<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryMarketerIdToCustomerContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_contracts', 'primary_marketer_id')) {
            Schema::table('customer_contracts', function (Blueprint $table) {
                $table->integer('primary_marketer_id')->nullable()->after('customer_id');
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
        Schema::table('customer_contracts', function (Blueprint $table) {
            $table->dropColumn("primary_marketer_id");
        });        
    }
}
