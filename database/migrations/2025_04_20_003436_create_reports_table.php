<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public $timestamp = false;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('reportID');
            $table->string('report_title');
            $table->string('report_type');
            $table->foreignId('userID')->references('userID')->on('users')->onDelete('cascade');
            $table->datetime('started_date');
            $table->datetime('end_date');
            $table->timestamp('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
