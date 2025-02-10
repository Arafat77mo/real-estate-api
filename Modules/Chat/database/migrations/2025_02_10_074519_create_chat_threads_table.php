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
        Schema::create('chat_threads', function (Blueprint $table) {
            $table->id();
            // The agent and the user involved in the conversation
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Add foreign keys (assuming you have a users table)
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Ensure that there is only one thread between the same agent and user
            $table->unique(['agent_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_threads');
    }
};
