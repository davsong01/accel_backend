<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryMarketerAndSecondaryMarketersToCustomerFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_forms', 'primary_marketer', 'secondary_marketers')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->integer('primary_marketer')->nullable()->after('marketers');
                $table->string('secondary_marketers')->nullable()->after('primary_marketer');
            });
        }

        if (Schema::hasColumn('customer_forms', 'marketers')) {
            Schema::table('customer_forms', function (Blueprint $table) {
                $table->dropColumn("marketers");
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
            $table->dropColumn("primary_marketer");
            $table->dropColumn("secondary_marketers");
        });        
    }
}
