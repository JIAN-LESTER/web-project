<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $timestamp = false;
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id('conversationID');
            $table->foreignId('userID')->references('userID')->on('users')->onDelete('cascade');
            $table->enum('conversation_status', ['active', 'answered', 'not_answered', 'ended']);
            $table->string('conversation_title')->nullable();
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
