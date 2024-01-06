<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerFormStatusToCustomerFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_forms', 'customer_form_status')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->string('customer_form_status')->default('pending')->after('status');
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
        Schema::table('customer_forms', function (Blueprint $table) {
            $table->dropColumn("customer_form_status");
        });        
    }
}
