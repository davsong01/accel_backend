<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerSettingsToEstateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('estate_settings', 'customer_settings')) {
            Schema::table('estate_settings', function (Blueprint $table) {
                $table->text('customer_settings')->nullable();
                $table->timestamp('expiry_date')->nullable();
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
