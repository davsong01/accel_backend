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

        if (Schema::hasColumns('customer_forms', ['contract_ids', 'title', 'no_of_contract','surname','first_name','middle_name','last_name','gender','date_of_birth','marital_status','lga_id','country_id', 'state_id', 'email_address', 'contact_details', 'next_of_kin_information', 'means_of_identification', 'other_details', 'primary_marketer', 'secondary_marketers','raw'])) {

            Schema::table('customer_forms', function (Blueprint $table) {
                $table->dropColumn(['contract_ids', 'title', 'no_of_contract', 'surname', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth', 'marital_status', 'lga_id', 'country_id', 'state_id', 'email_address', 'contact_details', 'next_of_kin_information', 'means_of_identification', 'other_details', 'primary_marketer', 'secondary_marketers','raw']);
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
