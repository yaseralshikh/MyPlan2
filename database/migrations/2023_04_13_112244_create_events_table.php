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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('note')->nullable();
            $table->date('start');
            $table->date('end');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('week_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->string('color')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('task_done')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
