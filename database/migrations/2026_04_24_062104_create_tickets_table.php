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
     Schema::create('tickets', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')->constrained();

        $table->string('customer_name');
        $table->string('email');
        $table->string('phone')->nullable();

        $table->text('description');

        $table->string('ref')->unique();

        $table->tinyInteger('status')
              ->default(0)
              ->comment('0=new, 1=attended, 2=resolved, 3=cancelled');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
