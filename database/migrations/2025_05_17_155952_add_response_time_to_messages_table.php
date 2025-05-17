<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->float('response_time', 8, 4)->nullable()->after('sent_at'); // e.g. 1.2345 seconds
        });
    }
    
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('response_time');
        });
    }
    
};
