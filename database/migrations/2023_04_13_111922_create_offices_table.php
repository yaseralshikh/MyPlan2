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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('director');
            $table->unsignedTinyInteger('allowed_overlap')->default(0);
            $table->unsignedTinyInteger('allowed_create_plans')->default(1);
            $table->string('director_signature_path', 2048)->nullable();
            $table->string('assistant_signature_path', 2048)->nullable();
            $table->string('assistant2_signature_path', 2048)->nullable();
            $table->string('assistant3_signature_path', 2048)->nullable();
            $table->foreignId('education_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('office_type')->default(0);
            $table->unsignedTinyInteger('gender')->default(1);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
