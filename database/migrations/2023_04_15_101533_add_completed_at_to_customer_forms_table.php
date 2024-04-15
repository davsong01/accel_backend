<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddCompletedAtToCustomerFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_forms', 'completed_at')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable()->before('created_at');
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
            $table->dropColumn("completed_at");
        });
    }
}
