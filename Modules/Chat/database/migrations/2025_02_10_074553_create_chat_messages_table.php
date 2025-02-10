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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_thread_id');
            $table->unsignedBigInteger('sender_id'); // The user who sent the message (agent or user)
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('chat_thread_id')->references('id')->on('chat_threads')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
