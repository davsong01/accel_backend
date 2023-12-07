
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
        if (!Schema::hasColumn('message_transactions', 'attachments')) {
            Schema::table('message_transactions', function (Blueprint $table) {
                $table->text('attachments')->nullable()->after('id');;
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('message_transactions', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
};