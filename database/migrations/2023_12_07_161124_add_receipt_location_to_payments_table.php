
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
        if (!Schema::hasColumn('payments', 'receipt_location')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->text('receipt_location')->nullable()->after('payment_slip');;
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('receipt_location');
        });
    }
};