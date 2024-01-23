
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
        if (!Schema::hasColumn('staff', 'bank_id','account_name','account_number','sort_code','swift_code','nhf_number','pfa_id','commission_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->integer('bank_id')->nullable();
                $table->integer('pfa_id')->nullable();
                $table->string('account_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('sort_code')->nullable();
                $table->string('swift_code')->nullable();
                $table->string('nhf_number')->nullable();
                $table->integer('commission_id')->nullable();
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('bank_id');
            $table->dropColumn('pfa_id');
            $table->dropColumn('account_name');
            $table->dropColumn('account_number');
            $table->dropColumn('sort_code');
            $table->dropColumn('swift_code');
            $table->dropColumn('nhf_number');
        });
    }
};