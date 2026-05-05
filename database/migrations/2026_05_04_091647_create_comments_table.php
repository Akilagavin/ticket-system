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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // The message content
            
            // Link to ticket - Cascade delete ensures comments are removed if a ticket is deleted
            $table->foreignId('ticket_id')
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Link to user - Must be nullable() because customers don't have accounts
            $table->foreignId('user_id')
                  ->nullable() 
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};