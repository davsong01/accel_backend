<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddRawToCustomerFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_forms', 'raw')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->longText('raw')->nullable()->after('company_id');
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
            $table->dropColumn("raw");
        });
    }
}
