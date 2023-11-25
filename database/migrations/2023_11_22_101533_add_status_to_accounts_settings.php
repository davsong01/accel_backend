<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddStatusToAccountsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('account_categories', 'status')) {
            Schema::table('account_categories', function (Blueprint $table) {
                $table->string('status')->default('inactive')->after('name');
            });
        }

        if (!Schema::hasColumn('account_groups', 'status')) {
            Schema::table('account_groups', function (Blueprint $table) {
                $table->string('status')->default('inactive')->after('name');
            });
        }

        if (!Schema::hasColumn('account_types', 'status')) {
            Schema::table('account_types', function (Blueprint $table) {
                $table->string('status')->default('inactive')->after('type');
            });
        }

        if (!Schema::hasColumn('chart_of_accounts', 'status')) {
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->string('status')->default('inactive')->after('name');
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
        Schema::table('account_categories', function (Blueprint $table) {
            $table->dropColumn("status");
        });

        Schema::table('account_groups', function (Blueprint $table) {
            $table->dropColumn("status");
        });

        Schema::table('account_types', function (Blueprint $table) {
            $table->dropColumn("status");
        });

        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropColumn("status");
        }); 
    }
}
