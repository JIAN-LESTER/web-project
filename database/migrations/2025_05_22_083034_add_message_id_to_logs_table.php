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
    Schema::table('logs', function (Blueprint $table) {
        $table->foreignId('messageID')->references('messageID')->on('messages')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('logs', function (Blueprint $table) {
        $table->dropForeign(['messageID']);
        $table->dropColumn('messageID');
    });
}

};
