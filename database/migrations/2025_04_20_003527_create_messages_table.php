<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $timestamp = false;

    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('messageID');
            $table->foreignId('userID')->references('userID')->on('users')->onDelete('cascade');
            $table->foreignId('kbID')->nullable()->references('kbID')->on('knowledge_bases')->onDelete('cascade');
            $table->foreignId('conversationID')->references('conversationID')->on('conversations')->onDelete('cascade');
            $table->text('content');
            $table->enum('messageStatus', ['sent', 'delivered', 'read']);
            $table->string('sender');
            $table->string('message_type');
            $table->float('response_time', 8, 4)->nullable()->after('sent_at'); // e.g. 1.2345 seconds
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('categoryID')->references('categoryID')->on('categories')->onDelete('cascade');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
