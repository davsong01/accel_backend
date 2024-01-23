<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        if (Schema::hasColumns('users', ['contact_information','additional_information','additional_details','means_of_identification','occupation', 'place_of_work', 'office_address'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('place_of_work');
                $table->dropColumn('occupation');
                $table->dropColumn('office_address');
                $table->dropColumn(['contact_information', 'additional_information', 'additional_details', 'means_of_identification']);
            });
        }

        if (!Schema::hasColumn('users', 'lga_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('lga_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('users', 'state_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('state_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('users', 'country_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('users', 'title')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('title')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('users', 'alternate_number')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('alternate_number')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'alternate_email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('alternate_email')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'marital_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('marital_status')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'occupation')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('occupation')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'office_address')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('office_address')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'place_of_work')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('place_of_work')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'id_card_type')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('id_card_type')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'id_card')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('id_card')->nullable();
            });
        }

        if (!Schema::hasColumn('customers', 'signature')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('signature')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
