<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddFilenameToStaffUploadedDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('staff_uploaded_documents', 'filename')) {
            Schema::table('staff_uploaded_documents', function (Blueprint $table) {
                $table->string('filename')->nullable()->after('file');
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
        Schema::table('staff_uploaded_documents', function (Blueprint $table) {
            $table->dropColumn("filename");
        });        
    }
}
